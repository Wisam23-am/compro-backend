<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/8b7da13865689338f5cae0f47629312231cbfd98',
                'name' => 'Angga Setiawan',
                'role' => 'Chief Executive Officer',
                'location' => 'Shanghai, China',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/69794fc706a2a906bde1f49ed6c08514ded68bcd',
                'name' => 'Shayna Liza',
                'role' => 'Product Manager',
                'location' => 'Bali, Indonesia',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/471a241318ff939760188814652d5abd63bf0f5e',
                'name' => 'Bruno Oleo',
                'role' => 'Customer Relations',
                'location' => 'Orchard, Singapore',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/d51b08192328abbfafaf7e1ab677ca81e134f3ee',
                'name' => 'Sami Kimi',
                'role' => 'Senior 3D Designer',
                'location' => 'Ho Chi Min, Vietnam',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/cbb178be795d2cde92beb9a21e3ed857afe2841d',
                'name' => 'Wibowo Putra',
                'role' => 'Senior 3D Designer',
                'location' => 'Ho Chi Min, Vietnam',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/ae4a3415faf5714698a4ab8c5a88c7f28ba19e9e',
                'name' => 'Putri Emily',
                'role' => 'Chief Executive Officer',
                'location' => 'Shanghai, China',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://api.builder.io/api/v1/image/assets/bbac1778046042629f3fc2333ccbaf93/9c8d297a4eab1102bfe44e4c78c7aa9c195f9112',
                'name' => 'Yuyan Chin',
                'role' => 'Product Manager',
                'location' => 'Bali, Indonesia',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('teams')->insert($teams);
    }
}
