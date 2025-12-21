<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Team Seeder
 * 
 * Seeds the database with sample team members for testing and demonstration.
 * Updated to use new Team model structure with position, is_active, and sort_order.
 */
class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding team members...');

        // Executive Team (High Priority)
        $teams = [
            [
                'image' => 'team-members/angga-setiawan.jpg',
                'name' => 'Angga Setiawan',
                'position' => 'Chief Executive Officer',
                'location' => 'Shanghai, China',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'image' => 'team-members/shayna-liza.jpg',
                'name' => 'Shayna Liza',
                'position' => 'Product Manager',
                'location' => 'Bali, Indonesia',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'image' => 'team-members/bruno-oleo.jpg',
                'name' => 'Bruno Oleo',
                'position' => 'Customer Relations',
                'location' => 'Orchard, Singapore',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'image' => 'team-members/sami-kimi.jpg',
                'name' => 'Sami Kimi',
                'position' => 'Senior 3D Designer',
                'location' => 'Ho Chi Min, Vietnam',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'image' => 'team-members/wibowo-putra.jpg',
                'name' => 'Wibowo Putra',
                'position' => 'Senior 3D Designer',
                'location' => 'Ho Chi Min, Vietnam',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'image' => 'team-members/putri-emily.jpg',
                'name' => 'Putri Emily',
                'position' => 'Chief Technology Officer',
                'location' => 'Shanghai, China',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'image' => 'team-members/yuyan-chin.jpg',
                'name' => 'Yuyan Chin',
                'position' => 'Senior Product Manager',
                'location' => 'Bali, Indonesia',
                'is_active' => true,
                'sort_order' => 7,
            ],
            // Additional team members
            [
                'image' => 'team-members/alex-morgan.jpg',
                'name' => 'Alex Morgan',
                'position' => 'Senior Full Stack Developer',
                'location' => 'Austin, TX',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'image' => 'team-members/jessica-park.jpg',
                'name' => 'Jessica Park',
                'position' => 'Frontend Developer',
                'location' => 'Seattle, WA',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'image' => 'team-members/ryan-martinez.jpg',
                'name' => 'Ryan Martinez',
                'position' => 'Backend Developer',
                'location' => 'Boston, MA',
                'is_active' => true,
                'sort_order' => 12,
            ],
            // Inactive member for testing
            [
                'image' => 'team-members/john-smith.jpg',
                'name' => 'John Smith',
                'position' => 'Former Developer',
                'location' => 'Remote',
                'is_active' => false,
                'sort_order' => 100,
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }

        $totalCount = count($teams);
        $activeCount = Team::active()->count();

        $this->command->info("✅ Successfully seeded {$totalCount} team members ({$activeCount} active, " . ($totalCount - $activeCount) . " inactive)");
    }
}

