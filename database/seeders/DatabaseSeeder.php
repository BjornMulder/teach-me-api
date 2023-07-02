<?php

namespace Database\Seeders;

use Database\Seeders\TopicSeeder;
use Database\Seeders\LearningBlockSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TopicSeeder::class,
            LearningBlockSeeder::class,
        ]);
    }
}
