<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('denda');
            }

            if (!Schema::hasColumn('peminjaman', 'struk_disetujui_at')) {
                $table->timestamp('struk_disetujui_at')->nullable()->after('catatan_admin');
            }

            if (!Schema::hasColumn('peminjaman', 'struk_disetujui_oleh')) {
                $table->foreignId('struk_disetujui_oleh')
                    ->nullable()
                    ->after('struk_disetujui_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::getConnection()->statement(
                "ALTER TABLE peminjaman MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pending'"
            );
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman', 'struk_disetujui_oleh')) {
                $table->dropConstrainedForeignId('struk_disetujui_oleh');
            }

            if (Schema::hasColumn('peminjaman', 'struk_disetujui_at')) {
                $table->dropColumn('struk_disetujui_at');
            }
        });
    }
};
