<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(300)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'is_active' => true
        ]);
        \App\Models\Role::factory()->create([
            'name_role' => 'Admin',
            'code_role' => '01',
        ]);
        \App\Models\Role::factory()->create([
            'name_role' => 'User',
            'code_role' => '02',
        ]);
    }
}
