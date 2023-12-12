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
        Schema::create('ride_route_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ride_id');
            $table->double('latitude');
            $table->double('longitude');
            $table->dateTime('timestamp');
            $table->double('speed');
            $table->integer('battery_charge_percent');

            $table->foreign('ride_id')->references('id')->on('rides');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_route_data');
    }
};
