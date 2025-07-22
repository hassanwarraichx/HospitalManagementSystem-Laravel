<?php

namespace App\Http\Controllers;

use App\Services\MedicineService;
use App\Exports\MedicineExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected MedicineService $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    public function index()
    {
        return view('reports.index');
    }

    public function exportMedicines()
    {
        return Excel::download(new MedicineExport, 'medicines.xlsx');
    }

}
