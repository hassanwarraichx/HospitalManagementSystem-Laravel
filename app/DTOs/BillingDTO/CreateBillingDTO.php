<?php

namespace App\DTOs\BillingDTO;

use App\DTOs\BaseDTO;

class CreateBillingDTO extends BaseDTO
{
    public function __construct(
        public float $consultation_fee,
        public ?float $medicine_fee = 0,
        public ?float $lab_fee = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            consultation_fee: $data['consultation_fee'],
            medicine_fee: $data['medicine_fee'] ?? 0,
            lab_fee: $data['lab_fee'] ?? 0
        );
    }

    public function total(): float
    {
        return $this->consultation_fee + $this->medicine_fee + $this->lab_fee;
    }


}
