<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'address' => fake('id_ID')->address(),
            'district' => 'Rungkut',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => rand(10000, 99999)
        ];
    }
}
