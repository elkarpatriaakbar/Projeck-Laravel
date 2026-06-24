<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create(
            [
            'name' => 'Elkar Patria Akbar',
            'email' => 'elkarpatriaakbar@mail.ugm.ac.id',
            'password' => bcrypt('admin123'),
        ]);

        User::factory()->create(
            [
            'name' => 'Elkar',
            'email' => 'elkar@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

    }
}
