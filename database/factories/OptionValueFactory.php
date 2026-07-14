<?php

namespace Database\Factories;

use App\Models\OptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Option;

/**
 * @extends Factory<OptionValue>
 */
class OptionValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'option_id' => Option::factory(),
            'value' => fake()->randomElement([
                'Black',
                'White',
                'Samsung',
                'Apple',
            ]),
            'slug' => fake()->slug(),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
