<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur
        $user = User::create([
            'name' => 'super-admin',
            'email' => 'gestionentreprisee@gmail.com',
            'password' => Hash::make('admin123')
        ]);

        // Créer un rôle
        $role = Role::create(['name' => 'super-admin']);

        // Obtenir toutes les permissions
        $permissions = Permission::pluck('id','id')->all();

        // Assigner les permissions au rôle
        $role->syncPermissions($permissions);

        // Assigner le rôle à l'utilisateur
        $user->assignRole([$role->id]);
    }
}
