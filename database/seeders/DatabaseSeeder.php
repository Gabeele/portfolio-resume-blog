<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(UserSeeder::class)->run();
        app(SkillSeeder::class)->run();
        app(CertificateSeeder::class)->run();
        app(WorkExperienceSeeder::class)->run();
        app(EducationSeeder::class)->run();
    }
}
