<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->paginate(10);
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'penulis' => 'required|string|max:100',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);
        
        // Simpan data (jika ada logika simpan buku sebelumnya, sesuaikan di sini)
    }

    // --- BAGIAN BARU: FITUR PINJAM BUKU ---
    public function pinjam(Request $request, $id)
    {
        $validated = $request->validate([
            'nim_peminjam' => 'required|string|max:15',
            'nama_peminjam' => 'required|string|max:150',
        ]);

        try {
            // Membungkus proses dalam DB::transaction untuk memenuhi syarat ACID
            DB::transaction(function () use ($validated, $id) {
                // Kunci baris buku menggunakan lockForUpdate() agar aman dari Race Condition
                $buku = Buku::where('id', $id)->lockForUpdate()->firstOrFail();

                // Tolak dan hentikan transaksi jika stok buku 0
                if ($buku->stok < 1) {
                    throw new \RuntimeException("Stok buku habis!");
                }

                // Cek apakah NIM tersebut masih meminjam buku ini dan belum dikembalikan
                $sedangDipinjam = Peminjaman::where('buku_id', $id)
                    ->where('nim_peminjam', $validated['nim_peminjam'])
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sedangDipinjam) {
                    throw new \RuntimeException("NIM ini sedang meminjam buku ini dan belum dikembalikan.");
                }

                // Kurangi stok buku
                $buku->decrement('stok');

                // Catat ke tabel peminjamans
                Peminjaman::create([
                    'kode_pinjam' => 'PJ-' . strtoupper(uniqid()),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $validated['nim_peminjam'],
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                    'status' => 'dipinjam',
                ]);
            }, 3);

            return back()->with('success', 'Buku berhasil dipinjam secara atomik!');
            
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            // Tangkap dan rekam error database sesuai instruksi
            Log::error('[DB EXCEPTION] Pinjam: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem database.');
        }
    }

    // --- BAGIAN BARU: FITUR KEMBALI BUKU ---
    public function kembali($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Kunci baris peminjaman
                $peminjaman = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($peminjaman->status === 'dikembalikan') {
                    throw new \RuntimeException("Buku sudah dikembalikan sebelumnya.");
                }

                // Ubah status dan catat tanggal kembali
                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // Kunci baris buku dan kembalikan stoknya
                $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->firstOrFail();
                $buku->increment('stok');
            }, 3);

            return back()->with('success', 'Buku berhasil dikembalikan!');
            
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION] Kembali: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem database.');
        }
    }
}