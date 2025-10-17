<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
  public function run(): void
{
    User::create([
        'name' => 'Proviseur ',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('password'),
    ]);

    // Créer les permissions
    $permissions = [
        'view_users',
        'create_users',
        'edit_users',
        'delete_users',
        'view_classes',
        'create_classes',
        'edit_classes',
        'delete_classes',
        'view_students',
        'create_students',
        'edit_students',
        'delete_students',
        'generate_certificates',
        'generate_cards',
        'generate_attendance_lists',
        'import_students',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    // Créer le rôle admin
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $admin->syncPermissions(Permission::all());

    // Créer le rôle secretary
    $secretary = Role::firstOrCreate(['name' => 'secretary']);
    // Permissions que tu souhaites donner au secrétaire, par exemple :
    $secretaryPermissions = [
        'view_students',
        'create_students',
        'edit_students',
        'view_classes',
        'generate_certificates',
    ];
    $secretary->syncPermissions($secretaryPermissions);

    // Assigner le rôle admin à l'utilisateur admin
    $adminUser = User::where('email', 'admin@gmail.com')->first();
    if ($adminUser) {
        $adminUser->assignRole('admin');
    }
}

}
