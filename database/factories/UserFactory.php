<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Provider\Fakecar;


class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'created_at' => now(),
            'role_id' => $this->faker->numberBetween(2, 3),
            'address' => $this->faker->address(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone_no' => $this->faker->phoneNumber(),
            'passport' => 'img/team/3.jpg',
            'bank' => $this->faker->name(),
            'meansOfIdentification' => $this->faker->randomElement(['Driver License']),
            'identificationDocument' => 'img/passport.jpg',
            'accountNumber' => $this->faker->numerify('###-###-####'),
            'accountName' => $this->faker->name(),
            'accountType' => $this->faker->randomElement(['savings','current'])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
