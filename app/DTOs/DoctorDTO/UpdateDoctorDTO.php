<?php

namespace App\DTOs\DoctorDTO;

use App\DTOs\BaseDTO;
use Illuminate\Http\UploadedFile;

class UpdateDoctorDTO extends BaseDTO
{

    public function __construct(
        public int $user_id,
        public string $name,
        public string $email,
        public ?string $password,
        public ?UploadedFile $profile_picture = null,
        public int $specialization_id,
        public ?array $availability = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            profile_picture: $data['profile_picture'] ?? null,
            specialization_id: $data['specialization_id'],
            availability: $data['availability'] ?? []
        );
    }

}
