<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'specialization_id',
        'qualification',
        'experience_years',
        'license_number',
        'bio',
        'consultation_fee',
        'available_days',
        'available_time_slots',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'available_days' => 'array',
        'available_time_slots' => 'array',
        'consultation_fee' => 'decimal:2',
    ];

    /**
     * Get the user that owns the doctor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialization that the doctor belongs to.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the patients associated with the doctor through appointments.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'appointments')
            ->withPivot('appointment_date', 'appointment_time', 'status', 'reason', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the reviews for the doctor.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
