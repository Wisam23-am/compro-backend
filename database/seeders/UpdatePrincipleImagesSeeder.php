<?php

namespace Database\Seeders;

use App\Models\Principle;
use Illuminate\Database\Seeder;

class UpdatePrincipleImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $principles = [
            [
                'id' => 1,
                'title' => 'Innovation First',
                'subtitle' => 'Leading with Creativity',
                'description' => 'We prioritize innovative solutions that drive progress and create value for our clients. Our team constantly explores new technologies and methodologies.',
                'icon' => 'https://img.icons8.com/fluency/96/innovation.png',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80',
            ],
            [
                'id' => 2,
                'title' => 'Professional Excellence',
                'subtitle' => 'Quality in Every Detail',
                'description' => 'Our commitment to professional excellence ensures that every project meets the highest standards. We deliver exceptional results through dedication and expertise.',
                'icon' => 'https://img.icons8.com/fluency/96/goal.png',
                'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&q=80',
            ],
            [
                'id' => 3,
                'title' => 'Sustainable Future',
                'subtitle' => 'Eco-Friendly Solutions',
                'description' => 'We believe in building a sustainable future through eco-friendly practices and green technologies. Environmental responsibility is at the core of our operations.',
                'icon' => 'https://img.icons8.com/fluency/96/leaf.png',
                'image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=800&q=80',
            ],
        ];

        foreach ($principles as $principleData) {
            $principle = Principle::find($principleData['id']);

            if ($principle) {
                $principle->update([
                    'title' => $principleData['title'],
                    'subtitle' => $principleData['subtitle'],
                    'description' => $principleData['description'],
                    'icon' => $principleData['icon'],
                    'image' => $principleData['image'],
                    'is_active' => true,
                ]);

                $this->command->info("Updated principle: {$principle->title}");
            } else {
                Principle::create([
                    'title' => $principleData['title'],
                    'subtitle' => $principleData['subtitle'],
                    'description' => $principleData['description'],
                    'icon' => $principleData['icon'],
                    'image' => $principleData['image'],
                    'is_active' => true,
                    'sort_order' => $principleData['id'],
                ]);

                $this->command->info("Created principle: {$principleData['title']}");
            }
        }

        $this->command->info('Principle images updated successfully!');
    }
}
