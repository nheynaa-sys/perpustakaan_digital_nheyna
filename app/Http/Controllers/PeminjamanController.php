<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Daftar peminjaman dengan filter status & tanggal
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_dari);
        }
        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_sampai);
        }

        // Anggota hanya lihat miliknya sendiri
        if (auth()->user()->role === 'anggota') {
            $anggota = Anggota::where('nis', auth()->user()->email)->first();
            if ($anggota) {
                $query->where('anggota_id', $anggota->id);
            } else {
                $query->whereNull('id');
            }
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Form peminjaman baru (admin)
     */
    public function create()
    {
        $buku    = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        $anggota = Anggota::orderBy('nama')->get();
        return view('peminjaman.create', compact('buku', 'anggota'));
    }

    /**
     * Simpan peminjaman baru oleh admin (langsung dipinjam)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'          => 'required|exists:anggota,id',
            'buku_id'             => 'required|exists:buku,id_buku',
            'tgl_pinjam'          => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        $sudahPinjam = Peminjaman::where('anggota_id', $validated['anggota_id'])
            ->where('buku_id', $validated['buku_id'])
            ->whereIn('status', ['dipinjam', 'pending'])
            ->exists();

        if ($sudahPinjam) {
            return back()->withInput()
                ->with('error', 'Anggota ini masih meminjam atau mengajukan buku yang sama.');
        }

        $buku = Buku::findOrFail($validated['buku_id']);
        if ($buku->stok <= 0) {
            return back()->withInput()->with('error', 'Stok buku tidak tersedia.');
        }

        $validated['status'] = 'dipinjam';
        $validated['denda']  = 0;
        Peminjaman::create($validated);
        $buku->decrement('stok');

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil disimpan.');
    }

    /**
     * Detail peminjaman
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'buku']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Struk peminjaman (halaman cetak) — akses admin & anggota pemilik
     */
    public function struk(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'buku', 'penyetujuStruk']);

        if (auth()->user()->role !== 'admin') {
            $anggota = Anggota::where('nis', auth()->user()->email)->first();
            if (!$anggota || $peminjaman->anggota_id !== $anggota->id || !$peminjaman->bolehDilihatAnggota()) {
                abort(403, 'Akses ditolak.');
            }
        }

        return view('peminjaman.struk', compact('peminjaman'));
    }

    // ─────────────────────────────────────────────
    // FITUR USER: Ajukan permintaan pinjam
    // ─────────────────────────────────────────────

    /**
     * Halaman katalog buku untuk user
     */
    public function katalog(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('pengarang', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->whereHas('kategori', fn($q) => $q->where('kategori.id', $request->kategori_id));
        }

        $buku      = $query->latest()->paginate(12)->withQueryString();
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();

        $anggota      = Anggota::where('nis', auth()->user()->email)->first();
        $bukuDipinjam = collect();
        if ($anggota) {
            $bukuDipinjam = Peminjaman::where('anggota_id', $anggota->id)
                ->whereIn('status', ['dipinjam', 'pending'])
                ->pluck('buku_id');
        }

        return view('user.katalog', compact('buku', 'kategoris', 'bukuDipinjam', 'anggota'));
    }

    /**
     * User mengajukan permintaan pinjam (status = pending)
     */
    public function ajukan(Request $request)
    {
        $anggota = Anggota::where('nis', auth()->user()->email)->first();

        if (!$anggota) {
            return back()->with('error', 'Akun Anda belum terdaftar sebagai anggota perpustakaan. Hubungi admin.');
        }

        $validated = $request->validate([
            'buku_id'             => 'required|exists:buku,id_buku',
            'tgl_kembali_rencana' => 'required|date|after:today',
        ], [
            'buku_id.required'             => 'Buku tidak valid.',
            'tgl_kembali_rencana.required' => 'Tanggal rencana kembali wajib diisi.',
            'tgl_kembali_rencana.after'    => 'Tanggal kembali harus setelah hari ini.',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis, tidak dapat mengajukan pinjam.');
        }

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id_buku)
            ->whereIn('status', ['dipinjam', 'pending'])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah meminjam atau mengajukan buku ini.');
        }

        Peminjaman::create([
            'anggota_id'          => $anggota->id,
            'buku_id'             => $buku->id_buku,
            'tgl_pinjam'          => Carbon::today()->toDateString(),
            'tgl_kembali_rencana' => $validated['tgl_kembali_rencana'],
            'status'              => 'pending',
            'denda'               => 0,
        ]);

        return back()->with('success', "Permintaan pinjam buku \"{$buku->judul}\" berhasil diajukan. Menunggu persetujuan admin.");
    }

    /**
     * Riwayat peminjaman user
     */
    public function riwayat()
    {
        $anggota    = Anggota::where('nis', auth()->user()->email)->first();
        $peminjaman = collect();

        if ($anggota) {
            $peminjaman = Peminjaman::with('buku')
                ->where('anggota_id', $anggota->id)
                ->latest()
                ->paginate(10);
        }

        return view('user.riwayat', compact('peminjaman', 'anggota'));
    }

    // ─────────────────────────────────────────────
    // FITUR ADMIN: Approve / Reject / Kembalikan
    // ─────────────────────────────────────────────

    /**
     * Daftar permintaan pending untuk admin
     */
    public function pending()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('peminjaman.pending', compact('peminjaman'));
    }

    /**
     * Admin menyetujui permintaan
     */
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $buku = $peminjaman->buku;
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis. Tidak dapat menyetujui.');
        }

        DB::transaction(function () use ($peminjaman, $buku) {
            $peminjaman->update([
                'status'               => 'dipinjam',
                'tgl_pinjam'           => Carbon::today()->toDateString(),
                'struk_disetujui_at'   => now(),
                'struk_disetujui_oleh' => auth()->id(),
            ]);
            $buku->decrement('stok');
        });

        return back()->with('success', "Permintaan \"{$buku->judul}\" oleh {$peminjaman->anggota->nama} disetujui.");
    }

    /**
     * Admin menolak permintaan
     */
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $peminjaman->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin ?? 'Permintaan ditolak oleh admin.',
        ]);

        return back()->with('success', 'Permintaan ditolak.');
    }

    /**
     * Proses pengembalian buku
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        DB::transaction(function () use ($peminjaman) {
            $tglAktual = Carbon::now()->toDateString();
            $selisih   = Carbon::parse($tglAktual)->diffInDays(
                Carbon::parse($peminjaman->tgl_kembali_rencana), false
            );

            if ($selisih < 0) {
                $peminjaman->denda  = (int) abs($selisih) * 1000;
                $peminjaman->status = 'terlambat';
            } else {
                $peminjaman->denda  = 0;
                $peminjaman->status = 'dikembalikan';
            }

            $peminjaman->tgl_kembali_aktual = $tglAktual;
            $peminjaman->struk_disetujui_at = now();
            $peminjaman->struk_disetujui_oleh = auth()->id();
            $peminjaman->save();
            $peminjaman->buku->increment('stok');
        });

        $pesan = $peminjaman->denda > 0
            ? 'Buku berhasil dikembalikan. Denda: Rp ' . number_format($peminjaman->denda, 0, ',', '.')
            : 'Buku berhasil dikembalikan tepat waktu.';

        return redirect()->route('peminjaman.index')->with('success', $pesan);
    }

    /**
     * Kirim struk ke halaman anggota.
     */
    public function approveStruk(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['dipinjam', 'dikembalikan', 'terlambat'], true)) {
            return back()->with('error', 'Struk hanya bisa dikirim untuk peminjaman yang sudah disetujui atau selesai.');
        }

        $peminjaman->update([
            'struk_disetujui_at' => now(),
            'struk_disetujui_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Struk berhasil dikirim ke halaman anggota.');
    }

    /**
     * Hapus data peminjaman (hanya admin)
     */
    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
