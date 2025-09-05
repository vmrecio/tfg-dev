<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('wedding_roles')) {
            Schema::drop('wedding_roles');
        }
    }

    public function down(): void
    {
        // No se recrea automáticamente
    }
};

