<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    /**
     * Daftar anggota dengan pencarian
     */
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nis', 'LIKE', "%{$search}%")
                  ->orWhere('kelas', 'LIKE', "%{$search}%");
            });
        }

        $anggota = $query->withCount(['peminjaman as pinjaman_aktif' => fn($q) => $q->where('status', 'dipinjam')])
                         ->latest()->paginate(15)->withQueryString();

        return view('anggota.index', compact('anggota'));
    }

    /**
     * Form tambah anggota
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Simpan anggota baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'      => 'required|string|max:20|unique:anggota,nis',
            'nama'     => 'required|string|max:100',
            'kelas'    => 'required|string|max:20',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nis.required'   => 'NIS wajib diisi.',
            'nis.unique'     => 'NIS sudah terdaftar.',
            'nama.required'  => 'Nama wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        $plainPassword = $request->filled('password') ? $request->password : $validated['nis'];

        $anggota = DB::transaction(function () use ($validated, $plainPassword) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['nis'],
                'password' => Hash::make($plainPassword),
                'role' => 'anggota',
            ]);

            $validated['password'] = Hash::make($plainPassword);
            $validated['user_id'] = $user->id;

            return Anggota::create($validated);
        });

        return redirect()->route('anggota.index')
            ->with('success', "Anggota \"{$anggota->nama}\" berhasil ditambahkan.");
    }

    /**
     * Detail anggota beserta riwayat peminjaman
     */
    public function show(Anggota $anggota)
    {
        $peminjaman = $anggota->peminjaman()
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('anggota.show', compact('anggota', 'peminjaman'));
    }

    /**
     * Form edit anggota
     */
    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update data anggota
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nis'      => 'required|string|max:20|unique:anggota,nis,' . $anggota->id,
            'nama'     => 'required|string|max:100',
            'kelas'    => 'required|string|max:20',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        // Hanya update password jika diisi
        $data = [
            'nis'    => $validated['nis'],
            'nama'   => $validated['nama'],
            'kelas'  => $validated['kelas'],
            'no_hp'  => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::transaction(function () use ($anggota, $data, $request) {
            $anggota->update($data);

            if ($anggota->user) {
                $userData = [
                    'name' => $anggota->nama,
                    'email' => $anggota->nis,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $anggota->user->update($userData);
            }
        });

        return redirect()->route('anggota.index')
            ->with('success', "Data anggota \"{$anggota->nama}\" berhasil diperbarui.");
    }

    /**
     * Hapus anggota
     */
    public function destroy(Anggota $anggota)
    {
        if ($anggota->hasPinjamanAktif()) {
            return back()->with('error', "Anggota \"{$anggota->nama}\" tidak dapat dihapus karena masih memiliki pinjaman aktif.");
        }

        $nama = $anggota->nama;
        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', "Anggota \"{$nama}\" berhasil dihapus.");
    }
}
