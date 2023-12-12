<?php

namespace Database\Factories;

use App\Models\Scooter;
use App\Models\Malfunction;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScooterMalfunction>
 */
class ScooterMalfunctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportTime = $this->faker->dateTimeBetween('-5 years', '-3 days');
        $repairTime = $this->faker->dateTimeInInterval($reportTime, '+3 days');

        return [
            'scooter_id' => Scooter::inRandomOrder()->first()->id,
            'malfunction_id' => Malfunction::inRandomOrder()->first()->id,
            'ride_id' => Ride::inRandomOrder()->first()->id,
            'reported_at' => $reportTime,
            'repaired_at' => $repairTime
        ];
    }
}
