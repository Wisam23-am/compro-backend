<?php

namespace Database\Seeders;

use App\Models\Award;
use Illuminate\Database\Seeder;

/**
 * Award Seeder
 * 
 * Seeds the awards table with sample data matching the frontend component.
 */
class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $awards = [
            [
                'title' => 'Solid Fundamental Crafter Async',
                'location' => 'Bali, 2020',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Most Crowded Yet Harmony Place',
                'location' => 'Shanghai, 2021',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Small Things Made Much Big Impacts',
                'location' => 'Zurich, 2022',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Teamwork and Solidarity',
                'location' => 'Bandung, 2023',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($awards as $award) {
            Award::create($award);
        }

        $this->command->info('Awards seeded successfully!');
    }
}
