<?php

namespace App\Services\Billing;

use App\DTOs\BillingDTO\CreateBillingDTO;
use App\Models\Appointment;

class BillingService
{
    public function createBill(Appointment $appointment, CreateBillingDTO $dto): void
    {
        $appointment->bill()->create([
            'consultation_fee' => $dto->consultation_fee,
            'medicine_fee' => $dto->medicine_fee,
            'lab_fee' => $dto->lab_fee,
            'total' => $dto->total(),
        ]);

    }
}
