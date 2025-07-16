<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_time',
        'status',     // 'pending', 'approved', 'rejected'
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class);
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}
