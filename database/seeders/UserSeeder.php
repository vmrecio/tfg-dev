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
            'name' => 'Víctor',
            'last_name' => 'Recio',
            'email' => 'victor@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::ADMIN);

        $couple1 = User::create([
            'name' => 'Félix',
            'last_name' => 'Blanco',
            'email' => 'felix@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple1->roles()->attach(Role::USER);

        $couple2 = User::create([
            'name' => 'Cristina',
            'last_name' => 'Cantos',
            'email' => 'cristina@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple2->roles()->attach(Role::USER);

        $vendor1 = User::create([
            'name' => 'Víctor',
            'last_name' => 'Morante',
            'email' => 'morante@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor1->roles()->attach(Role::USER);

        $vendor2 = User::create([
            'name' => 'Minerva',
            'last_name' => 'Mateos',
            'email' => 'minerva@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor2->roles()->attach(Role::USER);

        $guest1 = User::create([
            'name' => 'David',
            'last_name' => 'Casarrubio',
            'email' => 'deivid@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest1->roles()->attach(Role::USER);

        $guest2 = User::create([
            'name' => 'Ángela',
            'last_name' => 'García',
            'email' => 'angela@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest2->roles()->attach(Role::USER);
    }
}
