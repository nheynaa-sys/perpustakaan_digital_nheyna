<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'denda',
        'catatan_admin',
        'struk_disetujui_at',
        'struk_disetujui_oleh',
    ];

    protected $casts = [
        'tgl_pinjam'          => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual'  => 'date',
        'denda'               => 'integer',
        'struk_disetujui_at'  => 'datetime',
    ];

    /**
     * Relasi ke Anggota
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * Relasi ke Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'id_buku');
    }

    public function penyetujuStruk()
    {
        return $this->belongsTo(User::class, 'struk_disetujui_oleh');
    }

    /**
     * Hitung denda berdasarkan keterlambatan: Rp 1.000/hari
     */
    public function hitungDenda(): int
    {
        if (!$this->tgl_kembali_aktual) {
            return 0;
        }
        $selisih = $this->tgl_kembali_rencana->diffInDays($this->tgl_kembali_aktual, false);
        return $selisih > 0 ? (int) $selisih * 1000 : 0;
    }

    public function bolehDilihatAnggota(): bool
    {
        return $this->struk_disetujui_at !== null
            && in_array($this->status, ['dipinjam', 'dikembalikan', 'terlambat'], true);
    }

    public function getJenisStrukAttribute(): string
    {
        if ($this->denda > 0 || $this->status === 'terlambat') {
            return 'Struk Denda';
        }

        if ($this->status === 'dikembalikan') {
            return 'Struk Pengembalian';
        }

        return 'Struk Peminjaman';
    }

    /**
     * Accessor: format denda ke Rupiah
     */
    public function getDendaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    /**
     * Accessor: badge status berwarna (lengkap dengan pending & ditolak)
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'dipinjam'     => '<span class="badge bg-primary">Dipinjam</span>',
            'dikembalikan' => '<span class="badge bg-success">Dikembalikan</span>',
            'terlambat'    => '<span class="badge bg-danger">Terlambat</span>',
            'pending'      => '<span class="badge bg-warning text-dark">Pending</span>',
            'ditolak'      => '<span class="badge bg-secondary">Ditolak</span>',
            default        => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
