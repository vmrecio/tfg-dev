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
            'phone' => '+34 600000001',
            'email' => 'victor@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::ADMIN);

        $couple1 = User::create([
            'name' => 'Félix',
            'last_name' => 'Blanco',
            'phone' => '+34 600000011',
            'email' => 'felix@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple1->roles()->attach(Role::USER);

        $couple2 = User::create([
            'name' => 'Cristina',
            'last_name' => 'Cantos',
            'phone' => '+34 600000012',
            'email' => 'cristina@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $couple2->roles()->attach(Role::USER);

        $vendor1 = User::create([
            'name' => 'Víctor',
            'last_name' => 'Morante',
            'phone' => '+34 600000021',
            'email' => 'morante@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor1->roles()->attach(Role::USER);

        $vendor2 = User::create([
            'name' => 'Minerva',
            'last_name' => 'Mateos',
            'phone' => '+34 600000022',
            'email' => 'minerva@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendor2->roles()->attach(Role::USER);

        $guest1 = User::create([
            'name' => 'David',
            'last_name' => 'Casarrubio',
            'phone' => '+34 600000031',
            'email' => 'deivid@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest1->roles()->attach(Role::USER);

        $guest2 = User::create([
            'name' => 'Ángela',
            'last_name' => 'García',
            'phone' => '+34 600000032',
            'email' => 'angela@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guest2->roles()->attach(Role::USER);
    }
}
