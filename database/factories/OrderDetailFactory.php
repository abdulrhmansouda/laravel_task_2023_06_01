<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_price'    => fake()->randomFloat(2,0.01,9.99),
            'amount'            => fake()->numberBetween(1,5),
            'item_id'           => Item::inRandomOrder()->first()->id,
        ];
    }
}
