<?php

namespace Database\Seeders;

use App\Models\User;

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
        User::factory()->create([
            'name' => 'Emre Dikmen',
            'email' => 'emre@hotmail.com',
            'password' => Hash::make('123')
        ]);

        $this->call([
            CategorySeeder::class
        ]);
    }
}
