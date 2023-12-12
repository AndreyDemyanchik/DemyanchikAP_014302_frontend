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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('scooter_id');
            $table->dateTime('start');
            $table->dateTime('finish');
            $table->double('avg_speed');
            $table->double('distance');
            $table->double('price_total');
            $table->boolean('is_subscription_ride');

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('scooter_id')->references('id')->on('scooters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
