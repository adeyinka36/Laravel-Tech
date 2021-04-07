<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "sku"=>$this->faker->text(10),
            "description"=>$this->faker->text(50),
            "normal_price"=>$this->faker->randomDigit,
             "special_price"=>$this->faker->randomDigit
        ];
    }
}
