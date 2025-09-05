<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_item_user', function (Blueprint $table) {
            $table->foreignId('timeline_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['timeline_item_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_item_user');
    }
};
