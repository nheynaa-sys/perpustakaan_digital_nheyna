<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('buku')->latest()->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori = Kategori::create($validated);

        return redirect()->route('kategori.index')
            ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil ditambahkan.");
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategori->id,
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')
            ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil diperbarui.");
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->buku()->exists()) {
            return back()->with('error', "Kategori \"{$kategori->nama_kategori}\" tidak dapat dihapus karena masih digunakan oleh buku.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}