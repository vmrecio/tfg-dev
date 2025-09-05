<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('wedding_user') && ! Schema::hasTable('wedding_couple')) {
            Schema::rename('wedding_user', 'wedding_couple');
        }

        if (Schema::hasTable('wedding_couple') && Schema::hasColumn('wedding_couple', 'wedding_role_id')) {
            foreach ([
                'wedding_user_wedding_role_id_foreign',
                'wedding_couple_wedding_role_id_foreign',
            ] as $fk) {
                try { DB::statement("ALTER TABLE `wedding_couple` DROP FOREIGN KEY `{$fk}`"); } catch (\Throwable $e) {}
            }

            foreach ([
                'wedding_couple_wedding_role_id_index',
                'wedding_user_wedding_role_id_index',
            ] as $idx) {
                try { DB::statement("ALTER TABLE `wedding_couple` DROP INDEX `{$idx}`"); } catch (\Throwable $e) {}
            }

            Schema::table('wedding_couple', function (Blueprint $table) {
                $table->dropColumn('wedding_role_id');
            });
        }
    }

    public function down(): void
    {
        // No-op
    }
};

