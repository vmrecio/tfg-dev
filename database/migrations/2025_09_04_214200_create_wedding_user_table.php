<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_user', function (Blueprint $table) {
            $table->foreignId('wedding_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wedding_role_id')->constrained('wedding_roles')->onDelete('restrict');
            $table->string('status', 20)->default('active'); // active | pending | removed
            $table->timestamps();
            $table->primary(['wedding_id', 'user_id']);
            $table->index('wedding_role_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_user');
    }
};

