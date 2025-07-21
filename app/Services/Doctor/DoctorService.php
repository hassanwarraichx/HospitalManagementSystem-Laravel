<?php

namespace App\Services\Doctor;

use App\DTOs\DoctorDTO\CreateDoctorDTO;
use App\DTOs\DoctorDTO\UpdateDoctorDTO;
use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorService
{
    public function create(CreateDoctorDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $userData = [
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ];

            if ($dto->profile_picture instanceof UploadedFile) {
                $path = $dto->profile_picture->store('public/profile_picture');
                $userData['profile_picture'] = str_replace('public/', '', $path);
            }

            $user = User::create($userData);
            $user->assignRole('doctor');
            $cleanedAvailability = [];

            if (is_array($dto->availability)) {
                foreach ($dto->availability as $day => $slots) {
                    $validSlots = [];

                    foreach ($slots as $slot) {
                        if (!empty($slot['start']) && !empty($slot['end'])) {
                            $validSlots[] = [
                                'start' => $slot['start'],
                                'end' => $slot['end'],
                            ];
                        }
                    }

                    if (!empty($validSlots)) {
                        $cleanedAvailability[$day] = $validSlots;
                    }
                }
            }

            $profileData = [
                'specialization_id' => $dto->specialization_id,
                'availability' => $cleanedAvailability,
            ];

            $user->doctorProfile()->create($profileData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Helpers::log_request_to_db($e);
            throw $e;
        }
    }

    public function update(UpdateDoctorDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($dto->user_id);

            // Update basic user data
            $userData = [
                'name' => $dto->name,
                'email' => $dto->email,
            ];

            if (!empty($dto->password)) {
                $userData['password'] = Hash::make($dto->password);
            }

            if ($dto->profile_picture instanceof UploadedFile) {
                $path = $dto->profile_picture->store('public/profile_picture');
                $userData['profile_picture'] = str_replace('public/', '', $path);
            }

            $user->update($userData);

            // Filter and clean availability
            $cleanedAvailability = [];

            if (is_array($dto->availability)) {
                foreach ($dto->availability as $day => $slots) {
                    $validSlots = [];

                    foreach ($slots as $slot) {
                        if (!empty($slot['start']) && !empty($slot['end'])) {
                            $validSlots[] = [
                                'start' => $slot['start'],
                                'end' => $slot['end'],
                            ];
                        }
                    }

                    if (!empty($validSlots)) {
                        $cleanedAvailability[$day] = $validSlots;
                    }
                }
            }

            // Update doctor profile
            $profileData = [
                'specialization_id' => $dto->specialization_id,
                'availability' => $cleanedAvailability,
            ];

            $user->doctorProfile->update($profileData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Helpers::log_request_to_db($e);
            throw $e;
        }
    }

}
