<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Emre Dikmen',
            'email' => 'emre@hotmail.com',
            'password' => Hash::make('123'),
        ]);

        User::factory(20)->create();
    }
}
