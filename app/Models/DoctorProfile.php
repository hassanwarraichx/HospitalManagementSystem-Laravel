<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'specialization_id',
        'availability', // stored as JSON
    ];

    protected $casts = [
        'availability' => 'array',
    ];

    /**
     * Relationship: DoctorProfile belongs to a User.
     */
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Relationship: DoctorProfile belongs to a Specialization.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Relationship: A doctor has many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Accessor: Return doctor's full name with specialization (used in dropdowns).
     */
    public function getDisplayNameAttribute()
    {
        return $this->user->name . ' (' . ($this->specialization->name ?? 'General') . ')';
    }

    /**
     * Check if doctor is available on a given datetime.
     */
    public function isAvailableAt($datetime)
    {
        $availability = $this->availability;

        if (!$availability || !is_array($availability)) {
            return false;
        }

        $day = strtolower(now()->parse($datetime)->format('l')); // e.g., monday
        $time = now()->parse($datetime)->format('H:i');

        // Check if doctor has availability defined for that day
        if (!isset($availability[$day])) {
            return false;
        }

        foreach ($availability[$day] as $slot) {
            if ($time >= $slot['start'] && $time <= $slot['end']) {
                return true;
            }
        }

        return false;
    }
}
