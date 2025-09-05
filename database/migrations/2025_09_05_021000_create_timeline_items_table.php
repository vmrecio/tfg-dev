<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo','in_progress','done','blocked'])->default('todo');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['wedding_id', 'status']);
            $table->index(['wedding_id', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_items');
    }
};

