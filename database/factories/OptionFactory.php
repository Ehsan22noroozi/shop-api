<?php

namespace Database\Factories;

use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Color',
                'Size',
                'Brand',
                'Storage',
            ]),
            'slug' => fake()->slug(),
            'is_filterable' => true,
            'is_active' => true,
        ];
    }
}
