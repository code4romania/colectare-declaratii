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
        Schema::table('declarations', function (Blueprint $table) {
            $table->dateTime('started_processing_at')->nullable()->after('ip_address');
            $table->dateTime('finished_processing_at')->nullable()->after('started_processing_at');
            $table->dateTime('started_validation_at')->nullable()->after('started_processing_at');
            $table->dateTime('finished_validation_at')->nullable()->after('started_validation_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn([
                'started_processing_at',
                'finished_processing_at',
                'started_validation_at',
                'finished_validation_at',
            ]);
        });
    }
};
