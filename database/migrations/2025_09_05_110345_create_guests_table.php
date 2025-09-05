<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('side', 20)->nullable();
            $table->string('group')->nullable();
            $table->string('table_name')->nullable();
            $table->unsignedInteger('seats')->default(1);
            $table->string('invitation_token', 64)->unique();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->enum('rsvp_status', ['pending','accepted','declined'])->default('pending');
            $table->text('dietary')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->unique(['wedding_id', 'email']);
            $table->index(['wedding_id', 'rsvp_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
