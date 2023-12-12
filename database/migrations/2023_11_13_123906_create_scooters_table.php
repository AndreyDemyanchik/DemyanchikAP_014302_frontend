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
        Schema::create('scooters', function (Blueprint $table) {
            $table->id();
            $table->string('reg_number', 20);
            $table->string('maker', 30);
            $table->string('model', 50);
            $table->string('engine_power', 30);
            $table->double('weight');
            $table->double('max_speed');
            $table->double('unlock_price');
            $table->double('rate');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scooters');
    }
};
