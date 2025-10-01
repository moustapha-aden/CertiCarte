<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\SchoolYear;
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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Secretary User',
            'email' => 'secretary@example.com',
            'password' => bcrypt('password'),
            'role' => 'secretary',
        ]);

        $schoolYears = SchoolYear::factory(3)->create();

        foreach ($schoolYears as $schoolYear) {
            Classe::factory(6)->create([
                'year_id' => $schoolYear->id,
            ]);
        }

        $classes = Classe::all();
        foreach ($classes as $classe) {
            Student::factory(2)->create([
                'class_id' => $classe->id,
            ]);
        }
    }
}
