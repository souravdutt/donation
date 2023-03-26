<?php

namespace Database\Seeders;

use App\Models\Members;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 1; $i <= 3; $i++){
            $data[] = [
                'name' => $faker->name,
                'designation' => $faker->jobTitle,
                'quote' => $faker->sentence(),
                'image' => 'default.png',
            ];
        }
        Members::truncate();
        Members::insert($data);
    }
}
