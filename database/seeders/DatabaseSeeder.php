<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── USERS ──
        User::updateOrCreate(
            ['email' => 'admin@perpus.com'],
            ['name' => 'Administrator', 'email' => 'admin@perpus.com',
             'password' => Hash::make('password'), 'role' => 'admin']
        );
        User::updateOrCreate(
            ['email' => 'anggota@perpus.com'],
            ['name' => 'Siswa Demo', 'email' => 'anggota@perpus.com',
             'password' => Hash::make('password'), 'role' => 'anggota']
        );

        // ── KATEGORI ──
        $kategoris = ['Fiksi', 'Non-Fiksi', 'Sains & Teknologi', 'Sejarah', 'Pendidikan'];
        $kategoriIds = [];
        foreach ($kategoris as $nama) {
            $k = Kategori::updateOrCreate(
                ['nama_kategori' => $nama],
                ['deskripsi' => "Buku kategori {$nama}"]
            );
            $kategoriIds[$nama] = $k->id;
        }

        // ── BUKU ──
        $buku = [
            ['judul' => 'Bumi Manusia',          'pengarang' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra',     'tahun_terbit' => 1980, 'isbn' => '978-6027-65001-0', 'stok' => 5,  'deskripsi' => 'Novel sejarah Indonesia', 'kategori' => ['Fiksi', 'Sejarah']],
            ['judul' => 'Laskar Pelangi',         'pengarang' => 'Andrea Hirata',         'penerbit' => 'Bentang Pustaka', 'tahun_terbit' => 2005, 'isbn' => '978-9793062-96-9', 'stok' => 8,  'deskripsi' => 'Novel inspiratif',       'kategori' => ['Fiksi', 'Pendidikan']],
            ['judul' => 'Sapiens',                'pengarang' => 'Yuval Noah Harari',     'penerbit' => 'Gramedia',        'tahun_terbit' => 2014, 'isbn' => '978-6020-33614-0', 'stok' => 3,  'deskripsi' => 'Sejarah umat manusia',    'kategori' => ['Non-Fiksi', 'Sejarah']],
            ['judul' => 'The Pragmatic Programmer','pengarang' => 'David Thomas',          'penerbit' => 'Addison-Wesley',  'tahun_terbit' => 2019, 'isbn' => '978-0135957059',   'stok' => 4,  'deskripsi' => 'Panduan programmer',      'kategori' => ['Sains & Teknologi', 'Pendidikan']],
            ['judul' => 'Clean Code',             'pengarang' => 'Robert C. Martin',      'penerbit' => 'Prentice Hall',   'tahun_terbit' => 2008, 'isbn' => '978-0132350884',   'stok' => 2,  'deskripsi' => 'Menulis kode yang bersih','kategori' => ['Sains & Teknologi']],
            ['judul' => 'Harry Potter',           'pengarang' => 'J.K. Rowling',          'penerbit' => 'Scholastic',      'tahun_terbit' => 1997, 'isbn' => '978-0590353427',   'stok' => 10, 'deskripsi' => 'Novel fantasi',           'kategori' => ['Fiksi']],
            ['judul' => 'Atomic Habits',          'pengarang' => 'James Clear',           'penerbit' => 'Avery',           'tahun_terbit' => 2018, 'isbn' => '978-0735211292',   'stok' => 6,  'deskripsi' => 'Membangun kebiasaan',     'kategori' => ['Non-Fiksi', 'Pendidikan']],
            ['judul' => 'Fisika Dasar',           'pengarang' => 'Halliday & Resnick',    'penerbit' => 'Erlangga',        'tahun_terbit' => 2010, 'isbn' => '978-9790996670',   'stok' => 7,  'deskripsi' => 'Buku teks fisika',        'kategori' => ['Sains & Teknologi', 'Pendidikan']],
        ];

        foreach ($buku as $data) {
            $kategoriBuku = $data['kategori'];
            unset($data['kategori']);
            $b = Buku::updateOrCreate(['isbn' => $data['isbn']], $data);
            $ids = array_map(fn($k) => $kategoriIds[$k], $kategoriBuku);
            $b->kategori()->sync($ids);
        }

        // ── ANGGOTA ──
        $anggota = [
            ['nis' => '2024001', 'nama' => 'Andi Firmansyah',    'kelas' => 'XI RPL 1', 'no_hp' => '081234567890'],
            ['nis' => '2024002', 'nama' => 'Budi Santoso',       'kelas' => 'XI RPL 1', 'no_hp' => '082345678901'],
            ['nis' => '2024003', 'nama' => 'Citra Rahayu',       'kelas' => 'XI RPL 2', 'no_hp' => '083456789012'],
            ['nis' => '2024004', 'nama' => 'Denny Pratama',      'kelas' => 'XI RPL 2', 'no_hp' => '084567890123'],
            ['nis' => '2024005', 'nama' => 'Eka Putri Wulandari','kelas' => 'XI TKJ 1', 'no_hp' => '085678901234'],
        ];

        foreach ($anggota as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['nis']],
                [
                    'name' => $data['nama'],
                    'password' => Hash::make($data['nis']),
                    'role' => 'anggota',
                ]
            );

            Anggota::updateOrCreate(
                ['nis' => $data['nis']],
                $data + [
                    'password' => Hash::make($data['nis']),
                    'user_id' => $user->id,
                ]
            );
        }
    }
}
