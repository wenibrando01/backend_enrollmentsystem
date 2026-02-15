<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL', 'admin@example.com');
        $password = env('ADMIN_SEED_PASSWORD', 'secret123');
        $name = env('ADMIN_SEED_NAME', 'Administrator');

        User::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
            'password' => bcrypt($password),
            'is_admin' => true,
        ]);
    }
}
