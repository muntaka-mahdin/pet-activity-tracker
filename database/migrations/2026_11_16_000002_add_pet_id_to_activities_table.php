<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Nullable so existing rows survive the migration; the app always requires a pet.
            $table->foreignId('pet_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index(['pet_id', 'activity_date']);
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['pet_id', 'activity_date']);
            $table->dropConstrainedForeignId('pet_id');
        });
    }
};
