<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorUsers = User::where('role', 'doctor')->get();
        $specializationCount = 8; // Number of specializations we created

        foreach ($doctorUsers as $index => $user) {
            $specializationId = ($index % $specializationCount) + 1;
            $experienceYears = rand(2, 20);
            $consultationFee = rand(50, 200);

            Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $specializationId,
                'qualification' => $this->getRandomQualification(),
                'experience_years' => $experienceYears,
                'license_number' => 'LIC-' . rand(10000, 99999),
                'bio' => "Experienced doctor with $experienceYears years of practice in the field. Committed to providing high-quality healthcare services to patients.",
                'consultation_fee' => $consultationFee,
                'available_days' => $this->getRandomAvailableDays(),
                'available_time_slots' => $this->getRandomTimeSlots(),
            ]);
        }
    }

    /**
     * Get a random qualification.
     */
    private function getRandomQualification(): string
    {
        $qualifications = [
            'MBBS, MD',
            'MBBS, MS',
            'BDS, MDS',
            'BHMS, MD',
            'MBBS, DNB',
            'MD, DM',
            'MBBS, FRCS',
            'MD, PhD',
        ];

        return $qualifications[array_rand($qualifications)];
    }

    /**
     * Get random available days.
     */
    private function getRandomAvailableDays(): array
    {
        $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $availableDays = [];

        // Ensure at least 3 days are selected
        $numDays = rand(3, 6);

        // Shuffle the days and take the first $numDays
        shuffle($allDays);
        for ($i = 0; $i < $numDays; $i++) {
            $availableDays[] = $allDays[$i];
        }

        // Sort days in correct order
        $orderedDays = [];
        $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($dayOrder as $day) {
            if (in_array($day, $availableDays)) {
                $orderedDays[] = $day;
            }
        }

        return $orderedDays;
    }

    /**
     * Get random time slots.
     */
    private function getRandomTimeSlots(): array
    {
        $allTimeSlots = [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
        ];

        $timeSlots = [];

        // Ensure at least 3 time slots are selected
        $numSlots = rand(3, 8);

        // Shuffle the time slots and take the first $numSlots
        shuffle($allTimeSlots);
        for ($i = 0; $i < $numSlots; $i++) {
            $timeSlots[] = $allTimeSlots[$i];
        }

        // Sort time slots in chronological order
        sort($timeSlots);

        return $timeSlots;
    }
}
