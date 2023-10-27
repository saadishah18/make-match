<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Testing\Concerns\Has;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = ['male','female'];
        $random = substr(mt_rand(), 0, 13);

        return [
            'remember_token' => Str::random(10),
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->safeEmail,
            'email_verified_at' => now(),
            'password'=> Hash::make('12345678'),
            'phone'=> fake()->phoneNumber,
            'phone_verified_at' => now(),
            'address' => fake()->address,
            'gender' => array_rand($gender),
            'profile_image' => null,
            'id_card_number' => $random,
            'id_expiry' => '2025-12-31',
            'date_of_birth' => '1994-03-19',
            'id_card_front' => null,
            'id_card_back' => null,
            'selfie' => null,
            'qr_number' => generate_code(6),
            'role' => 'user'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
