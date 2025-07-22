<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicalHistoryExport;

class ExportController extends Controller
{
    public function exportHistory(PatientProfile $patient)
    {
        return Excel::download(new MedicalHistoryExport($patient), 'medical_history.xlsx');
    }
}
