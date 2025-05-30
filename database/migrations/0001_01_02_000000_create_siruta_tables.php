<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counties', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->primary();
            $table->string('code', 2)->unique();
            $table->string('name');
        });

        Schema::create('localities', function (Blueprint $table) {
            $table->mediumInteger('id')->unsigned()->primary();

            $table->smallInteger('county_id')->unsigned();
            $table->foreign('county_id')
                ->references('id')
                ->on('counties');

            $table->tinyInteger('level')->unsigned();

            $table->tinyInteger('type')->unsigned();

            $table->mediumInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('localities');

            $table->string('name');
        });

        Schema::withoutForeignKeyConstraints(function () {
            DB::unprepared(
                Storage::disk('seed-data')->get('siruta.sql')
            );
        });
    }
};
