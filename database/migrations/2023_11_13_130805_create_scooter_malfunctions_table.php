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
        Schema::create('scooter_malfunction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scooter_id');
            $table->unsignedBigInteger('malfunction_id');
            $table->unsignedBigInteger('ride_id');
            $table->dateTime('reported_at');
            $table->dateTime('repaired_at')->nullable();

            $table->foreign('scooter_id')->references('id')->on('scooters');
            $table->foreign('malfunction_id')->references('id')->on('malfunctions');
            $table->foreign('ride_id')->references('id')->on('rides');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scooter_malfunction');
    }
};
