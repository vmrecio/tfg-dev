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

        if (Schema::hasTable('wedding_couple') && Schema::hasColumn('wedding_couple', 'status')) {
            foreach ([
                'wedding_couple_status_index',
                'wedding_user_status_index',
            ] as $idx) {
                try { DB::statement("ALTER TABLE `wedding_couple` DROP INDEX `{$idx}`"); } catch (\Throwable $e) {}
            }

            Schema::table('wedding_couple', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('wedding_couple') && ! Schema::hasColumn('wedding_couple', 'status')) {
            Schema::table('wedding_couple', function (Blueprint $table) {
                $table->string('status', 20)->default('active');
                $table->index('status');
            });
        }
    }
};

