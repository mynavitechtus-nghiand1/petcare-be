<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'name'      => $name,
            'slug'      => \Illuminate\Support\Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'is_active' => true,
        ];
    }
}
