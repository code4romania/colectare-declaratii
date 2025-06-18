<?php

declare(strict_types=1);

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
            $table->timestamp('started_processing_at')->nullable();
            $table->timestamp('finished_processing_at')->nullable();
        });
    }
};
