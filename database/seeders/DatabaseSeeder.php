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
    }
}
