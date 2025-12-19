<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrincipleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $principles = [
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/0ab79c452129ddf5053f461a7d4a42a8deb400db',
                'icon' => 'Shield',
                'title' => 'Prioritize Trust',
                'description' => 'Shayna is an award-winning ametia construction company with lorem',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/22dba3343234a54bed1adb040ca131cedef16949',
                'icon' => 'Users',
                'title' => 'Professional People',
                'description' => 'Shayna is an award-winning ametia construction company with lorem',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/136d8bb6e31140d11dcd40e0c86eb671386827ef',
                'icon' => 'Leaf',
                'title' => 'Eco Friendly Concept',
                'description' => 'Shayna is an award-winning ametia construction company with lorem',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('principles')->insert($principles);
    }
}
