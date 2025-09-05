<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('wedding_user') && ! Schema::hasTable('wedding_couple')) {
            Schema::rename('wedding_user', 'wedding_couple');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('wedding_couple') && ! Schema::hasTable('wedding_user')) {
            Schema::rename('wedding_couple', 'wedding_user');
        }
    }
};

