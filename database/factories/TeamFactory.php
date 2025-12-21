<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common tech positions for realistic test data
        $positions = [
            'Chief Executive Officer',
            'Chief Technology Officer',
            'Chief Operations Officer',
            'Chief Marketing Officer',
            'Chief Financial Officer',
            'Senior Software Engineer',
            'Senior Product Manager',
            'Lead UX Designer',
            'Lead DevOps Engineer',
            'Data Scientist',
            'Senior Full Stack Developer',
            'Frontend Developer',
            'Backend Developer',
            'Product Designer',
            'Quality Assurance Engineer',
            'Business Analyst',
            'Project Manager',
            'Sales Manager',
            'Marketing Specialist',
            'Customer Success Manager',
        ];

        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement($positions),
            'location' => fake()->city() . ', ' . fake()->stateAbbr(),
            'image' => 'team-members/default-avatar.jpg',
            'is_active' => fake()->boolean(80), // 80% active
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the team member is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the team member is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set a specific position for the team member.
     */
    public function position(string $position): static
    {
        return $this->state(fn(array $attributes) => [
            'position' => $position,
        ]);
    }

    /**
     * Create an executive team member.
     */
    public function executive(): static
    {
        $executivePositions = [
            'Chief Executive Officer',
            'Chief Technology Officer',
            'Chief Operations Officer',
            'Chief Marketing Officer',
            'Chief Financial Officer',
        ];

        return $this->state(fn(array $attributes) => [
            'position' => fake()->randomElement($executivePositions),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 5),
        ]);
    }

    /**
     * Create a developer team member.
     */
    public function developer(): static
    {
        $developerPositions = [
            'Senior Software Engineer',
            'Senior Full Stack Developer',
            'Frontend Developer',
            'Backend Developer',
            'Lead DevOps Engineer',
        ];

        return $this->state(fn(array $attributes) => [
            'position' => fake()->randomElement($developerPositions),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(10, 50),
        ]);
    }

    /**
     * Set a sequential sort order.
     */
    public function sortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
