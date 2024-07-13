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
            'name' => 'Admin',
            'email' => 'gestionentreprisee@gmail.com',
            'name' => 'admin',
            'password' => Hash::make('admin123')
        ]);

        // Créer un rôle
        $role = Role::create(['name' => 'admin']); // i7oteha f tableau roles

        // Obtenir toutes les permissions
        $permissions = Permission::pluck('id','id')->all(); // role liha permissions kol  tjib donner mn permissions a3tito id el kol mtaapermissions

        // Assigner les permissions au rôle
        $role->syncPermissions($permissions); /// syncPermissions t3abi donner f table ismo roles has prmissions

        // Assigner le rôle à l'utilisateur
        $user->assignRole([$role->id]); ///  bch ta3ty role mta3k hethy tsob f model has user,assignRole taati role l user
    }
}
