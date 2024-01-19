<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(Customer::getGenders());
        Storage::fake('local');
        $imagePath = UploadedFile::fake()->image('avatar.jpg')->store('image', ['disk' => 'public']);

        return [
            'title' => fake()->title(strtolower($gender['label'])),
            'name' => fake('id_ID')->name(strtolower($gender['label'])),
            'gender' => $gender['value'],
            'phone_number' => fake('id_ID')->phoneNumber(),
            'image' => $imagePath,
            'email' => fake('id_ID')->unique()->safeEmail()
        ];
    }
}
