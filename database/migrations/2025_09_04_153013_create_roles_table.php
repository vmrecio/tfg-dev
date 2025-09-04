<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Usuario con acceso total al sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'couple',
                'display_name' => 'Novio',
                'description' => 'Miembro de la pareja, con visibilidad total de su evento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Proveedor',
                'description' => 'Proveedor, con acceso limitado a los eventos para los que está contratado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'guest',
                'display_name' => 'Invitado',
                'description' => 'Usuario con acceso limitado al evento al que ha sido invitado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};

