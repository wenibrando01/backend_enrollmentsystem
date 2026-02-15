<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ensure test user exists without duplicating
        \App\Models\User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password')
        ]);

        // Seed students and courses only if empty to avoid duplicate runs
        if (\App\Models\Student::count() === 0) {
            $this->call(StudentSeeder::class);
        }

        if (\App\Models\Course::count() === 0) {
            $this->call(CourseSeeder::class);
        }

        // Admin user (optional) - safe to run multiple times
        $this->call(AdminUserSeeder::class);
    }
}
