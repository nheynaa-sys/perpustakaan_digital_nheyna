<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('password');           // ✅ sudah include
            $table->foreignId('user_id')          // ✅ sudah include
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('nama');
            $table->string('kelas');
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};