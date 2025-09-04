<?php

namespace Database\Seeders;

use App\Models\Role;
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
       $admin = User::create([
            'name' => 'Administrador_TEST',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::ADMIN);

        $couple1 = User::create([
            'name' => 'Miembro_Pareja_1_TEST',
            'email' => 'couple@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple1->roles()->attach(Role::USER);

        $couple2 = User::create([
            'name' => 'Miembro_Pareja_2_TEST',
            'email' => 'couple2@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple2->roles()->attach(Role::USER);

        $vendor1 = User::create([
            'name' => 'Proveedor_1_TEST',
            'email' => 'vendor@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor1->roles()->attach(Role::USER);

        $vendor2 = User::create([
            'name' => 'Proveedor_2_TEST',
            'email' => 'vendor2@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor2->roles()->attach(Role::USER);

        $guest1 = User::create([
            'name' => 'Invitado_1_TEST',
            'email' => 'guest@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest1->roles()->attach(Role::USER);

        $guest2 = User::create([
            'name' => 'Invitado_2_TEST',
            'email' => 'guest2@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest2->roles()->attach(Role::USER);
    }
}