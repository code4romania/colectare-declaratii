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
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('county_id')
                ->unsigned();

            $table->foreign('county_id')
                ->references('id')
                ->on('counties');

            $table->mediumInteger('locality_id')
                ->unsigned();

            $table->foreign('locality_id')
                ->references('id')
                ->on('localities');

            $table->string('type');
            $table->string('full_name');
            $table->string('institution');
            $table->string('position');
            $table->string('filename');
            $table->string('original_filename');

            $table->ipAddress();
            $table->timestamps();
        });
    }
};
