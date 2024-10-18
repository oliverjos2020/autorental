<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $brand = $this->faker->unique()->randomElement([
            "Garki Station",
            "Lugbe Station",
            "Airport Road Station",
            "Nyanya Station",
            "Gwarinpa Station",
            "Dei Dei Station"

        ]);
        $location_id = $this->faker->unique()->numberBetween(1, 6);
        $slug = Str::slug($brand);
        return [
            'stationName' => $brand,
            'slug' => $slug,
            'location_id' => $location_id
        ];
    }
}
