<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

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
            'judul'        => 'required|string|max:150',
            'penulis'      => 'required|string|max:100',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori_id'  => 'required|exists:kategoris,id',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}