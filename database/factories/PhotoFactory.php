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
            'image_path' => $this->faker->randomElement(['img/cars/01.jpg','img/cars/02.jpg', 'img/cars/03.jpg', 'img/cars/04.jpg', 'img/cars/05.jpg', 'img/cars/06.jpg', 'img/cars/c2.jpg','img/cars/c3.jpg', 'img/cars/c4.jpg', 'img/cars/c5.jpg', 'img/cars/c7.jpg', 'img/cars/c8.jpg', 'img/cars/c9.jpg', 'assets-ii/media/content/b-goods/294x223/1.jpg','assets-ii/media/content/b-goods/294x223/2.jpg','assets-ii/media/content/b-goods/294x223/3.jpg','assets-ii/media/content/b-goods/294x223/4.jpg','assets-ii/media/content/b-goods/294x223/5.jpg','assets-ii/media/content/b-goods/360x260/2.jpg','assets-ii/media/content/b-goods/360x260/3.jpg','assets-ii/media/content/b-goods/360x260/10.jpg','assets-ii/media/content/b-goods/360x260/11.jpg','assets-ii/media/content/b-goods/360x260/12.jpg','assets-ii/media/content/b-goods/360x260/13.jpg']),
        ];
    }
}
