<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try { DB::statement('ALTER TABLE `timeline_items` DROP INDEX `timeline_items_wedding_id_due_at_index`'); } catch (\Throwable $e) {}

        if (Schema::hasColumn('timeline_items', 'due_at')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->dropColumn('due_at');
            });
        }

        if (Schema::hasColumn('timeline_items', 'position')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->dropColumn('position');
            });
        }

        if (! Schema::hasColumn('timeline_items', 'location')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->string('location')->nullable()->after('end_at');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('timeline_items', 'due_at')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->timestamp('due_at')->nullable()->after('end_at');
            });
            try { DB::statement('ALTER TABLE `timeline_items` ADD INDEX `timeline_items_wedding_id_due_at_index` (`wedding_id`, `due_at`)'); } catch (\Throwable $e) {}
        }

        if (! Schema::hasColumn('timeline_items', 'position')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->unsignedInteger('position')->default(0);
            });
        }

        if (Schema::hasColumn('timeline_items', 'location')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
};
