<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\BillingDTO\CreateBillingDTO;
use App\Exports\PrescriptionExport;
use App\Exports\SingleBillExport;
use App\Exports\SinglePrescriptionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBillRequest;
use App\Models\Appointment;
use App\Services\Billing\BillingService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BillingController extends Controller
{
    private BillingService $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'prescription'])
            ->whereHas('prescription')
            ->whereDoesntHave('bill')
            ->get();

        return view('admin.billing.index', compact('appointments'));
    }

    public function create(Appointment $appointment)
    {
        return view('admin.billing.create', compact('appointment'));
    }

    public function store(StoreBillRequest $request, Appointment $appointment)
    {
        $dto = CreateBillingDTO::fromArray($request->validated());

        $this->billingService->createBill($appointment, $dto);

        return redirect()->route('admin.billing.index')->with('success', '✅ Bill generated successfully.');
    }

    public function exportBill(Appointment $appointment)
    {
        return Excel::download(new SingleBillExport($appointment), 'bill_' . $appointment->id . '.xlsx');
    }

    public function exportPrescription(Appointment $appointment)
    {
        return Excel::download(new SinglePrescriptionExport($appointment), 'prescription_' . $appointment->id . '.xlsx');
    }
}
