<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('timeline_items', 'status')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('timeline_items', 'status')) {
            Schema::table('timeline_items', function (Blueprint $table) {
                $table->enum('status', ['todo','in_progress','done','blocked'])->default('todo');
            });
        }
    }
};
