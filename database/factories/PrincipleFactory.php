<?php

namespace Database\Factories;

use App\Models\Principle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Principle Factory
 * 
 * Generates fake data for Principle model for testing and seeding.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Principle>
 */
class PrincipleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Principle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'subtitle' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(3),
            'icon' => 'principles/icons/' . $this->faker->uuid() . '.svg',
            'image' => 'principles/images/' . $this->faker->uuid() . '.jpg',
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the principle is active.
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the principle is inactive.
     *
     * @return static
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the principle has no image.
     *
     * @return static
     */
    public function withoutImage(): static
    {
        return $this->state(fn(array $attributes) => [
            'image' => null,
        ]);
    }

    /**
     * Indicate that the principle has no icon.
     *
     * @return static
     */
    public function withoutIcon(): static
    {
        return $this->state(fn(array $attributes) => [
            'icon' => null,
        ]);
    }

    /**
     * Indicate that the principle has a specific sort order.
     *
     * @param  int  $order
     * @return static
     */
    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
