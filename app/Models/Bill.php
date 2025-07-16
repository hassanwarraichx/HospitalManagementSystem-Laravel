<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'consultation_fee',
        'lab_fee',
        'medicine_fee',
        'total',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
