<?php

namespace Database\Factories;

use App\Models\Ride;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RideRouteData>
 */
class RideRouteDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ride_id' => Ride::inRandomOrder()->first()->id,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'timestamp' => $this->faker->dateTime,
            'speed' => $this->faker->randomFloat(2, 3, 40),
            'battery_charge_percent' => $this->faker->numberBetween(1, 100),
            'created_at' => $this->faker->dateTimeBetween('-3 years', 'now')
        ];
    }
}
