<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SponsorshipSeeder::class,
            VoteSeeder::class,
            SpecializationSeeder::class,
            UserSeeder::class,
            ChefSeeder::class,
            ReviewSeeder::class,
            MessageSeeder::class,
            ChefSponsorshipSeeder::class,
            ChefSpecializationSeeder::class,
            ChefVoteSeeder::class,
        ]);
    }
}
