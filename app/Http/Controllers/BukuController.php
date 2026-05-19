<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku (dengan pencarian & filter kategori)
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Pencarian berdasarkan judul, pengarang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('pengarang', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->whereHas('kategori', fn($q) => $q->where('kategori.id', $request->kategori_id));
        }

        $buku      = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('buku.index', compact('buku', 'kategoris'));
    }

    /**
     * Form tambah buku
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Simpan buku baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:200',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:buku,isbn',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori_id'  => 'nullable|array',
            'kategori_id.*'=> 'exists:kategori,id',
        ], [
            'judul.required'    => 'Judul buku wajib diisi.',
            'pengarang.required'=> 'Nama pengarang wajib diisi.',
            'isbn.unique'       => 'ISBN sudah digunakan buku lain.',
            'stok.required'     => 'Stok wajib diisi.',
            'cover.image'       => 'File cover harus berupa gambar.',
            'cover.max'         => 'Ukuran cover maksimal 2 MB.',
        ]);

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku = Buku::create($validated);

        // Sync kategori (many-to-many)
        if ($request->filled('kategori_id')) {
            $buku->kategori()->sync($request->kategori_id);
        }

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil ditambahkan.");
    }

    /**
     * Detail buku
     */
    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'peminjaman.anggota']);
        return view('buku.show', compact('buku'));
    }

    /**
     * Form edit buku
     */
    public function edit(Buku $buku)
    {
        $kategoris       = Kategori::orderBy('nama_kategori')->get();
        $selectedKategori = $buku->kategori->pluck('id')->toArray();
        return view('buku.edit', compact('buku', 'kategoris', 'selectedKategori'));
    }

    /**
     * Update data buku
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:200',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:buku,isbn,' . $buku->id_buku . ',id_buku',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori_id'  => 'nullable|array',
            'kategori_id.*'=> 'exists:kategori,id',
        ]);

        // Upload cover baru jika ada, hapus yang lama
        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($validated);

        // Sync kategori
        $buku->kategori()->sync($request->input('kategori_id', []));

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil diperbarui.");
    }

    /**
     * Hapus buku
     */
    public function destroy(Buku $buku)
    {
        // Cek apakah buku masih dipinjam
        if ($buku->peminjaman()->where('status', 'dipinjam')->exists()) {
            return back()->with('error', "Buku \"{$buku->judul}\" tidak dapat dihapus karena masih dipinjam.");
        }

        // Hapus cover jika ada
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $judul = $buku->judul;
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus.");
    }
}