<?php

namespace App\Http\Controllers;

use App\DTOs\MedicineData;
use App\Http\Requests\StoreMedicineRequest;
use App\Models\Medicine;
use App\Services\MedicineService;

class MedicineController extends Controller
{
    protected MedicineService $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    public function index()
    {
        $medicines = Medicine::all();
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(StoreMedicineRequest $request)
    {
        $dto = new MedicineData($request->validated());
        $this->medicineService->create($dto);

        return redirect()->back()->with('success', 'Medicine added!');
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(StoreMedicineRequest $request, Medicine $medicine)
    {
        $dto = new MedicineData($request->validated());
        $this->medicineService->update($medicine, $dto);

        return redirect()->route("medicines.index")->with('success', 'Medicine updated!');

    }

    public function destroy(Medicine $medicine)
    {
        $this->medicineService->delete($medicine);

        return redirect()->back()->with('success', 'Medicine deleted!');
    }

}
