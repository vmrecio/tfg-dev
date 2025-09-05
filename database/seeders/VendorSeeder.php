<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorSpecialty;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeamos los emails para asegurarnos que creamos esos usuarios en el seeder de usuarios
        $entries = [
            [
                'email' => 'morante@example.com',
                'specialty' => 'finca',
                'company_name' => 'Finca Morante',
            ],
            [
                'email' => 'minerva@example.com',
                'specialty' => 'fotografia',
                'company_name' => 'Minerva Foto',
            ],
        ];

        foreach ($entries as $e) {
            $user = User::where('email', $e['email'])->first();
            if (! $user) {
                continue; // skip if user not found
            }

            $specId = VendorSpecialty::where('name', $e['specialty'])->value('id');

            Vendor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'vendor_specialty_id' => $specId,
                    'company_name' => $e['company_name'] ?? null,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]
            );
        }
    }
}
