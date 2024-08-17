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
        $location = $this->faker->city();
        $slug = Str::slug($location);
        return [
            'location' => $location,
            'slug' => $slug
        ];
    }
}
