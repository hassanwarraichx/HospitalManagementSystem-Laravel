<?php

namespace App\Services;

use App\DTOs\MedicineData;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineService
{
    public function create(MedicineData $data): Medicine
    {
        return Medicine::create($data->toArray());
    }

    public function update(Medicine $medicine, MedicineData $data): Medicine
    {
        $medicine->update($data->toArray());
        return $medicine;
    }

    public function delete(Medicine $medicine): void
    {
        $medicine->delete();
    }

    public function lowStock(int $threshold = 10)
    {
        return Medicine::where('stock', '<', $threshold)->get();
    }

    public function nearExpiry(int $days = 30)
    {
        return Medicine::whereBetween('expiry_date', [now(), now()->addDays($days)])->get();
    }
}
