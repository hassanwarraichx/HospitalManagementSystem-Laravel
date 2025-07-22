<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\DoctorDTO\CreateDoctorDTO;
use App\DTOs\DoctorDTO\UpdateDoctorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDoctorRequest;
use App\Http\Requests\Admin\UpdateDoctorRequest;
use App\Models\Specialization;
use App\Models\User;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\Request;

class   DoctorController extends Controller
{
    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(){
        $doctors = User::role('doctor')->with('doctorProfile.specialization')->latest()->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(){
        $specializations = Specialization::all();
        return view('admin.doctors.create', compact('specializations'));
    }

    public function store(StoreDoctorRequest $request)
    {
        //dd($request->all());
        $dto = CreateDoctorDTO::fromArray($request->validated());
        $this->doctorService->create($dto);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor registered successfully.');
    }
    public function edit(User $doctor)
    {
        $specializations = Specialization::all();
        $doctor->load('doctorProfile');
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }


    public function update(UpdateDoctorRequest $request, User $doctor)
    {
        $data = $request->validated();
        $data['user_id'] = $doctor->id;

        $dto = UpdateDoctorDTO::fromArray($data);
        $this->doctorService->update($dto);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }


    public function destroy(User $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor soft deleted successfully.');
    }


}
