<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Pending peminjaman (admin only)
        $pendingPeminjaman = collect();
        $pendingCount      = 0;

        if (auth()->user()->role === 'admin') {
            $pendingPeminjaman = Peminjaman::with(['anggota', 'buku'])
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $pendingCount = Peminjaman::where('status', 'pending')->count();
        }

        // Kartu statistik
        $totalBuku    = Buku::count();
        $totalAnggota = Anggota::count();
        $dipinjam     = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda   = Peminjaman::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('denda');

        // 5 buku terpopuler
        $bukuTerpopuler = Buku::withCount([
            'peminjaman' => function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            }
        ])
            ->orderByDesc('peminjaman_count')
            ->having('peminjaman_count', '>', 0)
            ->take(5)
            ->get();

        // Peminjaman terlambat
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali_rencana', '<', Carbon::today())
            ->with(['buku', 'anggota'])
            ->latest()
            ->get();

        // Laporan 6 bulan terakhir
        $laporanBulanan = Peminjaman::selectRaw(
            'YEAR(created_at) as tahun,
             MONTH(created_at) as bulan,
             COUNT(*) as jumlah,
             SUM(denda) as total_denda'
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('tahun ASC, bulan ASC')
            ->get()
            ->map(function ($row) {
                $row->label = Carbon::createFromDate($row->tahun, $row->bulan, 1)
                    ->translatedFormat('M Y');
                return $row;
            });

        // Peminjaman terbaru
        $pinjamanTerbaru = Peminjaman::with(['buku', 'anggota'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'pendingPeminjaman',
            'pendingCount',
            'totalBuku',
            'totalAnggota',
            'dipinjam',
            'totalDenda',
            'bukuTerpopuler',
            'terlambat',
            'laporanBulanan',
            'pinjamanTerbaru'
        ));
    }
}