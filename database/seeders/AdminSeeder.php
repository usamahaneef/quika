<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(3)->create();

        \App\Models\User::factory(3)->create([
            'name' => 'quika',
            'email' => 'quika@admin.com',
            'password' => Hash::make('admin2233'),
        ]);
    }
}
