<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            UserSeeder::class,
            MemberSeeder::class,
            AlbumSeeder::class,
        ]);

        # Create `countries`, `states` and `cities` table and seed data.
        $this->command->info('Seeding countries, states and cities from external sql files. This might take few moments!');
        $world = "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/sql/world.sql";
        DB::unprepared(file_get_contents($world));
        $this->command->info('Successfully seeded countries, states and cities!');
    }
}
