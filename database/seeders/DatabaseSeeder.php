<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Student;
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

        User::factory(2)->create();
        Classe::factory(9)->create();
        Student::factory(10)->create();
    }
}
