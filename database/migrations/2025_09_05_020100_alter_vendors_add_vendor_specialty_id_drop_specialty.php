<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'vendor_specialty_id')) {
                $table->foreignId('vendor_specialty_id')->nullable()->after('user_id')
                    ->constrained('vendor_specialties')->nullOnDelete();
            }
            if (Schema::hasColumn('vendors', 'specialty')) {
                $table->dropColumn('specialty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'specialty')) {
                $table->string('specialty')->nullable()->after('company_name');
            }
            if (Schema::hasColumn('vendors', 'vendor_specialty_id')) {
                $table->dropConstrainedForeignId('vendor_specialty_id');
            }
        });
    }
};
