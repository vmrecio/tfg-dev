<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });

        DB::table('vendor_specialties')->insert([
            ['name' => 'wedding-planner', 'display_name' => 'Wedding Planner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'fotografia', 'display_name' => 'Fotografía', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'catering', 'display_name' => 'Catering', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'agencia-viajes', 'display_name' => 'Agencia de viajes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'finca', 'display_name' => 'Finca', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dj', 'display_name' => 'DJ', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_specialties');
    }
};

