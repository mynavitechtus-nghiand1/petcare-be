<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'brand_id'     => \App\Models\Brand::factory(),
            'name'         => $name,
            'slug'         => \Illuminate\Support\Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'sku'          => strtoupper($this->faker->unique()->bothify('??-###-???')),
            'product_type' => 'single',
            'status'       => 'published',
        ];
    }
}
