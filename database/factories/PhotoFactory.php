<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_id' => $this->faker->numberBetween(1, 50),
            // 'vehicle_id' => $this->faker->randomElement([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52]),
            'image_path' => $this->faker->randomElement(['assets/images/small/img-1.jpg', 'assets/images/small/img-2.jpg', 'assets/images/small/img-3.jpg', 'assets/images/small/img-4.jpg', 'assets/images/small/img-5.jpg', 'assets/images/small/img-6', 'assets/images/small/img-7.jpg']),
        ]; 
    }
}
