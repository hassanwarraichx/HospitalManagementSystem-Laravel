<?php

namespace App\DTOs\PatientDTO;

use App\DTOs\BaseDTO;
use Illuminate\Http\UploadedFile;

class CreatePatientDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $dob,
        public string $gender,
        public string $address,
        public ?string $phone = null,
        public ?UploadedFile $profile_picture = null,
        public array $medical_histories = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['dob'],
            $data['gender'],
            $data['address'],
            $data['phone'] ?? null,
            $data['profile_picture'] ?? null,
            $data['medical_histories'] ?? []

        );
    }


}
