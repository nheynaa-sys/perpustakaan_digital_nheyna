<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kategori', function (Blueprint $table) {
            $table->unsignedBigInteger('buku_id');
            $table->unsignedBigInteger('kategori_id');

            $table->foreign('buku_id')->references('id_buku')->on('buku')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');

            $table->primary(['buku_id', 'kategori_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kategori');
    }
};