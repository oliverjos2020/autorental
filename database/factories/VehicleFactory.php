<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2, 11),
            'status' => $this->faker->numberBetween(2, 3),
            'driverLicense' => $this->faker->randomElement(['yes','no']),
            'transmission' => $this->faker->randomElement(['automatic','manual']),
            'doors' => $this->faker->numberBetween(2, 4),
            'seats' => $this->faker->numberBetween(2, 16),
            'vehicleMake' => $this->faker->randomElement([
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
                "Ferrari"
            ]),
            'vehicleModel' => $this->faker->randomElement([
                "Camry",
                "Civic",
                "Mustang",
                "Impala",
                "Altima",
                "3 Series",
                "C-Class",
                "Golf",
                "A4",
                "Elantra",
                "Sorento",
                "Outback",
                "Mazda3",
                "RX",
                "XE",
                "911",
                "Model S",
                "XC90",
                "Range Rover",
                "488"
            ]),
            'passengers' => $this->faker->numberBetween(2, 16),
            'category_id' => $this->faker->numberBetween(1, 3),
            'airCondition' => $this->faker->randomElement(['yes','no']),
            'location' => $this->faker->city(),
            'price_setup_id' => $this->faker->numberBetween(1, 4),
            'dateApproved' => now()
        ];
    }
}
