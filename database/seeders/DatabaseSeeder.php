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

        $schoolYears = SchoolYear::factory(4)->create();

        // Create 20 classes total (5 classes per school year)
        foreach ($schoolYears as $schoolYear) {
            Classe::factory(5)->create([
                'year_id' => $schoolYear->id,
            ]);
        }

        $classes = Classe::all();

        // Ensure at least 45 students in each class
        foreach ($classes as $classe) {
            Student::factory(45)->create([
                'classe_id' => $classe->id,
            ]);
        }

        // Optionally create some additional students without specific classes
        Student::factory(50)->create();
    }
}
