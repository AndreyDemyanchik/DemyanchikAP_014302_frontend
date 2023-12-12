<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'card_number' => $this->faker->creditCardNumber('Visa'),
            'card_expiration_date' => $this->faker->creditCardExpirationDate(),
            'is_subscription_active' => $this->faker->boolean,
            'created_at' => $this->faker->dateTimeBetween('-3 years', 'now')
        ];
    }
}
