<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'blood_group',
        'allergies',
        'medical_history',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the patient profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the doctors associated with the patient through appointments.
     */
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'appointments')
            ->withPivot('appointment_date', 'appointment_time', 'status', 'reason', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the reviews created by the patient.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
