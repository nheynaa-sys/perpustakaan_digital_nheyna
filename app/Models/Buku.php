<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'cover',
        'deskripsi',
    ];

    /**
     * Relasi many-to-many ke Kategori
     */
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori', 'buku_id', 'kategori_id');
    }

    /**
     * Relasi one-to-many ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id', 'id_buku');
    }

    /**
     * Accessor: mendapatkan URL cover buku
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover && file_exists(public_path('storage/' . $this->cover))) {
            return asset('storage/' . $this->cover);
        }
        return asset('images/no-cover.png');
    }
}