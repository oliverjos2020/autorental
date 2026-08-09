<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $location = $this->faker->city();
        $location = $this->faker->unique()->randomElement([
            "Garki Station",
            "Lugbe Station",
            "Airport Road Station",
            "Nyanya Station",
            "Gwarinpa Station",
            "Dei Dei Station"

        ]);
        $slug = Str::slug($location);
        return [
            'location' => $location,
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
