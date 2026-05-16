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
        app(ShieldSeeder::class)->run();
        app(UserSeeder::class)->run();
        app(SkillSeeder::class)->run();
        app(CertificateSeeder::class)->run();
        app(WorkExperienceSeeder::class)->run();
        app(EducationSeeder::class)->run();
        app(ProjectSeeder::class)->run();
        app(ReferenceSeeder::class)->run();
        app(SummarySeeder::class)->run();

    }
}
