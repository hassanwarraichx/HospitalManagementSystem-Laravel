<?php
// Seeder: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Specialization;
use Spatie\Permission\Models\Role;
use App\Models\Medicine;



class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = ['admin', 'doctor', 'patient'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Specializations
        $specializations = ['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology'];
        foreach ($specializations as $name) {
            Specialization::firstOrCreate(['name' => $name]);
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Doctors
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Doctor $i",
                'email' => "doctor$i@example.com",
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('doctor');

            $user->doctorProfile()->create([
                'specialization_id' => rand(1, count($specializations)),
                'availability' => ['mon' => '9-5', 'tue' => '9-5'],
            ]);
        }

        // Create Patients
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Patient $i",
                'email' => "patient$i@example.com",
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('patient');

            $user->patientProfile()->create([
                'dob' => now()->subYears(rand(18, 60))->format('Y-m-d'),
                'gender' => $i % 2 == 0 ? 'female' : 'male',
                'address' => "Address $i"
            ]);
        }
        // Create Medicines
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'brand' => 'ABC Pharma',
                'stock' => 200,
                'expiry_date' => now()->addMonths(12),
                'price' => 5.50,
            ],
            [
                'name' => 'Ibuprofen 200mg',
                'brand' => 'XYZ Pharma',
                'stock' => 150,
                'expiry_date' => now()->addMonths(10),
                'price' => 8.75,
            ],
            [
                'name' => 'Amoxicillin 250mg',
                'brand' => 'MediCare',
                'stock' => 80,
                'expiry_date' => now()->addMonths(6),
                'price' => 12.00,
            ],
            [
                'name' => 'Cough Syrup 100ml',
                'brand' => 'Herbal Labs',
                'stock' => 50,
                'expiry_date' => now()->addMonths(5),
                'price' => 6.25,
            ],
            [
                'name' => 'Vitamin D3 1000 IU',
                'brand' => 'SunPharma',
                'stock' => 120,
                'expiry_date' => now()->addMonths(14),
                'price' => 3.50,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::firstOrCreate(
                ['name' => $medicine['name']], 
                [
                    'brand' => $medicine['brand'],
                    'stock' => $medicine['stock'],
                    'expiry_date' => $medicine['expiry_date'],
                    'price' => $medicine['price'],
                ]
            );
        }

         
    }
}
