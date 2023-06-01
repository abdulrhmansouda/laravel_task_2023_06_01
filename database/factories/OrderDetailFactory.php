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
            'purchase_price'    => fake()->randomFloat(8,0.01,999999.99),
            'amount'            => fake()->numberBetween(1,999),
            'item_id'           => Item::inRandomOrder()->first()->id,
        ];
    }
}
