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
       $admin = User::updateOrCreate(
            ['email' => 'victor@example.com'],
            [
                'name' => 'Víctor',
                'last_name' => 'Recio',
                'phone' => '+34 600000001',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([Role::ADMIN]);

        $couple1 = User::updateOrCreate(
            ['email' => 'felix@example.com'],
            [
                'name' => 'Félix',
                'last_name' => 'Blanco',
                'phone' => '+34 600000011',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $couple1->roles()->syncWithoutDetaching([Role::USER]);

        $couple2 = User::updateOrCreate(
            ['email' => 'cristina@example.com'],
            [
                'name' => 'Cristina',
                'last_name' => 'Cantos',
                'phone' => '+34 600000012',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $couple2->roles()->syncWithoutDetaching([Role::USER]);

        $vendor1 = User::updateOrCreate(
            ['email' => 'morante@example.com'],
            [
                'name' => 'Víctor',
                'last_name' => 'Morante',
                'phone' => '+34 600000021',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $vendor1->roles()->syncWithoutDetaching([Role::USER]);

        $vendor2 = User::updateOrCreate(
            ['email' => 'minerva@example.com'],
            [
                'name' => 'Minerva',
                'last_name' => 'Mateos',
                'phone' => '+34 600000022',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $vendor2->roles()->syncWithoutDetaching([Role::USER]);

        $guest1 = User::updateOrCreate(
            ['email' => 'deivid@example.com'],
            [
                'name' => 'David',
                'last_name' => 'Casarrubio',
                'phone' => '+34 600000031',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $guest1->roles()->syncWithoutDetaching([Role::USER]);

        $guest2 = User::updateOrCreate(
            ['email' => 'angela@example.com'],
            [
                'name' => 'Ángela',
                'last_name' => 'García',
                'phone' => '+34 600000032',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $guest2->roles()->syncWithoutDetaching([Role::USER]);
    }
}
