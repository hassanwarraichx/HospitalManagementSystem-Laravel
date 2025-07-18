<?php

namespace App\DTOs\PatientDTO;

use App\DTOs\BaseDTO;

class UpdatePatientDTO extends BaseDTO
{
    public function __construct(
        public int $user_id,
        public string $email,
        public string $address,
        public string $phone,
        public ?array $medical_histories = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['user_id'],
            $data['email'],
            $data['address'],
            $data['phone'],
            $data['medical_histories'] ?? null,
        );
    }

}
