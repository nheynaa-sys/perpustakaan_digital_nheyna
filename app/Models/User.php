<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',   // Untuk admin: email asli. Untuk anggota: NIS.
        'password',
        'role',    // 'admin' | 'anggota'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel anggota.
     */
    public function anggota(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Helper: cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: cek apakah user adalah anggota.
     */
    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }
}