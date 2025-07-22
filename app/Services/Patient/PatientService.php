<?php

namespace App\Services\Patient;

use App\DTOs\PatientDTO\CreatePatientDTO;
use App\DTOs\PatientDTO\UpdatePatientDTO;
use App\Helpers\Helpers;
use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientService
{

    public function create(CreatePatientDTO $dto): void
    {
        DB::beginTransaction();

        try {
            // ✅ Create User
            $userData = collect($dto->toArray())->only(['name', 'email', 'password'])->toArray();
            $userData['password'] = Hash::make($userData['password']);

            // ✅ Store profile picture (optional)
            if ($dto->profile_picture) {
                $path = $dto->profile_picture->store('public/profile_picture');
                $userData['profile_picture'] = str_replace('public/', '', $path);
            }

            $user = User::create($userData);
            $user->assignRole('patient');

            // ✅ Create Patient Profile
            $profileData = collect($dto->toArray())->only(['dob', 'gender', 'address', 'phone'])->toArray();
            $user->patientProfile()->create($profileData);

            // ✅ Create Medical Histories (optional)
            foreach ($dto->medical_histories as $history) {
                $description = $history['description'] ?? null;
                $document = $history['document'] ?? null;

                // 🔁 Skip entry if both fields are empty
                if (empty($description) && empty($document)) {
                    continue;
                }

                $path = null;
                if ($document) {
                    $path = $document->store('medical_documents', 'public');
                }

                $user->medicalHistories()->create([
                    'description' => $description,
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


    public function update(UpdatePatientDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($dto->user_id);

            $user->update(['email' => $dto->email]);

            $user->patientProfile->update([
                'address' => $dto->address,
                'phone' => $dto->phone,
            ]);

            $existingIds = $user->medicalHistories()->pluck('id')->toArray();
            $submittedIds = [];

            if ($dto->medical_histories) {
                foreach ($dto->medical_histories as $entry) {
                    if (!empty($entry['id'])) {
                        $submittedIds[] = $entry['id']; // track submitted ones
                        $history = MedicalHistory::find($entry['id']);
                        if ($history) {
                            $history->description = $entry['description'] ?? '';

                            if (isset($entry['document']) && $entry['document'] instanceof UploadedFile) {
                                $path = $entry['document']->store('public/medical_documents');
                                $history->document_path = str_replace('public/', '', $path);
                            }

                            $history->save();
                        }
                    } else {
                        $user->medicalHistories()->create([
                            'description' => $entry['description'] ?? '',
                            'document_path' => isset($entry['document']) && $entry['document'] instanceof UploadedFile
                                ? str_replace('public/', '', $entry['document']->store('public/medical_documents'))
                                : null,
                        ]);
                    }
                }
            }

            $toDelete = array_diff($existingIds, $submittedIds);
            MedicalHistory::whereIn('id', $toDelete)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Helpers::log_error_to_db($e);
            throw $e;
        }
    }




}
