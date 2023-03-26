<?php

namespace Database\Seeders;

use App\Models\Albums;
use App\Models\Media;
use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 1; $i <= 20; $i++){
            $data[] = [
                'name' => $faker->sentence,
                'description' => $faker->paragraph(3),
            ];
            for($j = 1; $j <= 3; $j++){
                $media[] = [
                    'album_id' => $i,
                    'name' => 'default.png',
                    'type' => 'image',
                ];
            }
        }
        Albums::truncate();
        Albums::insert($data);

        Media::truncate();
        Media::insert($media);
    }
}
