<?php

namespace Database\Factories;

use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scooter>
 */
class ScooterFactory extends Factory
{
    private const REG_NUMBER_PREFIXES = [
        'AE',
        'AD',
    ];

    private array $regNumbersSet = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->generateSetOfRandomRegNumbers();
        $this->faker->addProvider(new Fakecar($this->faker));
        $vehicle = $this->faker->vehicleArray();

        return [
            'reg_number' => $this->faker->lexify($this->getRandomRegNumberPrefix() . '-' . $this->getRandomRegNumber()),
            'maker' => $vehicle['brand'],
            'model' => $vehicle['model'],
            'engine_power' => $this->faker->bothify(),
            'weight' => $this->faker->numberBetween(10, 50),
            'max_speed' => $this->faker->numberBetween(20, 50),
            'unlock_price' => $this->faker->numberBetween(5, 10),
            'rate' => $this->faker->numberBetween(5, 10),
            'created_at' => $this->faker->dateTimeBetween('-3 years', 'now')
        ];
    }

    /**
     * @return string
     */
    private function getRandomRegNumberPrefix(): string
    {
        return ScooterFactory::REG_NUMBER_PREFIXES[array_rand(ScooterFactory::REG_NUMBER_PREFIXES, 1)];
    }

    /**
     * @return int
     */
    private function getRandomRegNumber(): int
    {
        return $this->regNumbersSet[array_rand($this->regNumbersSet, 1)];
    }

    /**
     * @return void
     */
    private function generateSetOfRandomRegNumbers(): void
    {
        $digits = 4;

        for ($i = 0; $i < 10; $i++) {
            $this->regNumbersSet[] = rand(pow(10, $digits-1), pow(10, $digits)-1);
        }
    }
}
