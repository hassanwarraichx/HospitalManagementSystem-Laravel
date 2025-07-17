<?php

namespace App\Services\Patient;

use App\DTOs\PatientDTO\CreatePatientDTO;
use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientService
{

    public function create(CreatePatientDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $userData = collect($dto->toArray())->only(['name', 'email', 'password'])->toArray();
            $userData['password'] = Hash::make($userData['password']);
            if($dto->profile_picture){
                $path = $dto->profile_picture->store('public/profile_picture');
                $userData['profile_picture'] = str_replace('public/', '', $path);
            }
            $user = User::create($userData);
            $user->assignRole('patient');

            $profileData = collect($dto->toArray())->only(['dob', 'gender', 'address', 'phone'])->toArray();
            $user->patientProfile()->create($profileData);

            // NEW: Handle medical histories
            foreach ($dto->medical_histories as $history) {
                $path = null;

                if (isset($history['document']) && $history['document']) {
                    $path = $history['document']->store('medical_documents');
                }

                $user->medicalHistories()->create([
                    'description' => $history['description'],
                    'document_path' => $path,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Helpers::log_error_to_db($e);
            throw $e;
        }
    }


}
