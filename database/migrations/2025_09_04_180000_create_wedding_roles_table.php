<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('wedding_roles')->insert([
            [
                'name' => 'couple',
                'display_name' => 'Pareja',
                'description' => 'Miembro de la pareja con acceso completo a su boda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Proveedor',
                'description' => 'Proveedor vinculado a servicios de la boda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'planner',
                'display_name' => 'Organizador',
                'description' => 'Organizador/planificador con permisos de gestión',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_roles');
    }
};

