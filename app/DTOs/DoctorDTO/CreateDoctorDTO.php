<?php

namespace App\DTOs\DoctorDTO;

use App\DTOs\BaseDTO;
use Illuminate\Http\UploadedFile;

class CreateDoctorDTO extends BaseDTO
{

    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?UploadedFile $profile_picture = null,
        public int $specialization_id,
        public ?array $availability = null
    ) {}

    public static function fromArray(array $data): self
    {
        //dd($data);
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            profile_picture: $data['profile_picture'] ?? null,
            specialization_id: $data['specialization_id'],
            availability: $data['availability'] ?? []
        );
    }
}
