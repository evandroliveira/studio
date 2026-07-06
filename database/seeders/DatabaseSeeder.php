<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminEmail = env('OWNER_EMAIL', 'admin@studio.com');
        $adminPassword = env('OWNER_PASSWORD', 'Admin12345');

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'password' => Hash::make($adminPassword),
                'role' => User::ROLE_ADMIN,
            ]
        );

        if (! $admin->isAdmin()) {
            $admin->forceFill([
                'role' => User::ROLE_ADMIN,
            ])->save();
        }
    }
}
