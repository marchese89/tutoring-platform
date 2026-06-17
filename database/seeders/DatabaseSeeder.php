<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('private')->deleteDirectory('demo');

        $this->call([
            DemoUserSeeder::class,
            DemoCatalogSeeder::class,
            DemoLessonRequestSeeder::class,
            DemoReviewSeeder::class,
            DemoOrderSeeder::class,
            DemoChatSeeder::class,
        ]);
    }
}
