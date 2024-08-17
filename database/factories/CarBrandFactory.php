<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CarBrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $brand = $this->faker->unique()->randomElement([
        "Toyota",
        "Honda",
        "Ford",
        "Chevrolet",
        "Nissan",
        "BMW",
        "Mercedes-Benz",
        "Volkswagen",
        "Audi",
        "Hyundai",
        "Kia",
        "Subaru",
        "Mazda",
        "Lexus",
        "Jaguar",
        "Porsche",
        "Tesla",
        "Volvo",
        "Land Rover",
        "Ferrari",
    ]);
    $slug = Str::slug($brand);
    return [
        'brand' => $brand,
        'slug' => $slug,
    ];

    }
}
