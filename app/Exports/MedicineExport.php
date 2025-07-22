<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;

class MedicineExport implements FromCollection
{
    public function collection()
    {
        return Medicine::all();
    }
}


