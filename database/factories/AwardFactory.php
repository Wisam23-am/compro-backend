<?php

namespace Database\Factories;

use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Award::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Bali, Indonesia',
            'Shanghai, China',
            'Zurich, Switzerland',
            'Bandung, Indonesia',
            'Singapore',
            'Tokyo, Japan',
            'New York, USA',
            'London, UK',
            'Paris, France',
            'Sydney, Australia',
        ];

        $titles = [
            'Solid Fundamental Crafter Async',
            'Most Crowded Yet Harmony Place',
            'Small Things Made Much Big Impacts',
            'Teamwork and Solidarity',
            'Innovation Excellence Award',
            'Best User Experience Design',
            'Outstanding Technical Achievement',
            'Customer Satisfaction Award',
            'Best Workplace Culture',
            'Sustainable Business Practice',
        ];

        return [
            'title' => fake()->unique()->randomElement($titles),
            'location' => fake()->randomElement($cities) . ', ' . fake()->year(),
            'featured' => fake()->boolean(30), // 30% chance of being featured
            'is_active' => fake()->boolean(90), // 90% chance of being active
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the award is featured.
     */
    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'featured' => true,
        ]);
    }

    /**
     * Indicate that the award is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the award is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
