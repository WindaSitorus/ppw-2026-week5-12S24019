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
    // --- FITUR BARU: INDEX DENGAN STATISTIK & PENCARIAN ---
    public function index(Request $request)
    {
        // Menyimpan riwayat pencarian terakhir ke Session (Syarat Modul)
        if ($request->filled('search')) {
            session(['last_buku_search' => $request->search]);
        }

        // 1. Eloquent withCount & Filter Pencarian Dinamis
        $bukus = Buku::with('kategori')
            ->withCount('peminjamans')
            ->when($request->search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('penulis', 'like', "%{$search}%")
                             ->orWhere('isbn', 'like', "%{$search}%")
                             ->orWhereHas('kategori', function($q) use ($search) {
                                 $q->where('nama_kategori', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Mempertahankan parameter pencarian di pagination

        // 2. Query Builder (DB::table) untuk Analitik Perpustakaan
        $stats = [
            'total_buku'         => DB::table('bukus')->count(),
            'total_stok'         => DB::table('bukus')->sum('stok'),
            'sedang_dipinjam'    => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
            'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
        ];

        return view('buku.index', compact('bukus', 'stats'));
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
        
        // Simpan data
    }

    // --- FITUR PINJAM BUKU ---
    public function pinjam(Request $request, $id)
    {
        $validated = $request->validate([
            'nim_peminjam' => 'required|string|max:15',
            'nama_peminjam' => 'required|string|max:150',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                $buku = Buku::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($buku->stok < 1) {
                    throw new \RuntimeException("Stok buku habis!");
                }

                $sedangDipinjam = Peminjaman::where('buku_id', $id)
                    ->where('nim_peminjam', $validated['nim_peminjam'])
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sedangDipinjam) {
                    throw new \RuntimeException("NIM ini sedang meminjam buku ini dan belum dikembalikan.");
                }

                $buku->decrement('stok');

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
            Log::error('[DB EXCEPTION] Pinjam: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem database.');
        }
    }

    // --- FITUR KEMBALI BUKU ---
    public function kembali($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $peminjaman = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($peminjaman->status === 'dikembalikan') {
                    throw new \RuntimeException("Buku sudah dikembalikan sebelumnya.");
                }

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

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