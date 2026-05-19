<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('anggota')
            ->whereNull('user_id')
            ->orderBy('id')
            ->get()
            ->each(function ($anggota) {
                $user = DB::table('users')->where('email', $anggota->nis)->first();

                if (!$user) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $anggota->nama,
                        'email' => $anggota->nis,
                        'password' => $anggota->password,
                        'role' => 'anggota',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $userId = $user->id;

                    DB::table('users')->where('id', $userId)->update([
                        'name' => $anggota->nama,
                        'role' => 'anggota',
                        'updated_at' => now(),
                    ]);
                }

                DB::table('anggota')->where('id', $anggota->id)->update([
                    'user_id' => $userId,
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        //
    }
};
