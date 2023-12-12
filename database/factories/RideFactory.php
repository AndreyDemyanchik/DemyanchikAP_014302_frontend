<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Scooter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-10 days', '-1 day');
        $finish = $this->faker->dateTimeInInterval($start, '+5 hours');

        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'scooter_id' => Scooter::inRandomOrder()->first()->id,
            'start' => $start,
            'finish' => $finish,
            'avg_speed' => $this->faker->randomFloat(2, 1, 50),
            'distance' => $this->faker->randomFloat(2, 1, 50),
            'price_total' => $this->faker->randomFloat(2, 5, 100),
            'is_subscription_ride' => $this->faker->boolean,
            'created_at' => $this->faker->dateTimeBetween('-3 years', 'now')
        ];
    }
}
