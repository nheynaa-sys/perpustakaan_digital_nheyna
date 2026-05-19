<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nis',
        'password',
        'user_id',
        'nama',
        'kelas',
        'no_hp',
        'alamat',
    ];

    protected $hidden = ['password'];

    // ── Relasi ──────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ✅ Relasi ke peminjaman
     */
    public function peminjaman(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

    // ── Helper ──────────────────────────────────────────────

    /**
     * ✅ Cek pinjaman aktif — dipakai di destroy()
     */
    public function hasPinjamanAktif(): bool
    {
        return $this->peminjaman()
                    ->whereIn('status', ['pending', 'dipinjam'])
                    ->exists();
    }
}
