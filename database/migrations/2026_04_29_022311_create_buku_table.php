<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id('id_buku');
            $table->string('judul', 200);
            $table->string('pengarang', 100);
            $table->string('penerbit', 100)->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn', 20)->unique()->nullable();
            $table->integer('stok')->default(0);
            $table->string('cover')->nullable(); // path gambar cover
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};