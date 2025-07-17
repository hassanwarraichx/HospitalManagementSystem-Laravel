<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\PatientDTO\CreatePatientDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePatientRequest;
use App\Models\User;
use App\Services\Patient\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected PatientService $patientService;
    public function __construct(PatientService $patientService){
        $this->patientService = $patientService;
    }

    public function index(){
        $patients = User::role('patient')->with('patientProfile')->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function create(){
        return view('admin.patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        $medicalHistories = [];

        if ($request->has('medical_histories')) {
            foreach ($request->medical_histories as $index => $history) {
                $medicalHistories[] = [
                    'description' => $history['description'],
                    'document' => $request->file("medical_histories.$index.document") ?? null,
                ];
            }
        }

        $validated['medical_histories'] = $medicalHistories;

        $dto = CreatePatientDTO::fromArray($validated);
        $this->patientService->create($dto);

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully');
    }





}
