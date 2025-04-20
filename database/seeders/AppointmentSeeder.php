<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        // Create 30 random appointments
        for ($i = 0; $i < 30; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();

            // Random date between 30 days ago and 30 days from now
            $date = Carbon::now()->subDays(30)->addDays(rand(0, 60));

            // If date is in the past, appointment is more likely to be completed or cancelled
            $statusProbabilities = $date->isPast()
                ? [10, 30, 50, 10]  // past: 10% pending, 30% confirmed, 50% completed, 10% cancelled
                : [30, 60, 0, 10];  // future: 30% pending, 60% confirmed, 0% completed, 10% cancelled

            $statusIndex = $this->getRandomIndexByProbability($statusProbabilities);
            $status = $statuses[$statusIndex];

            // Get available time slots for the doctor
            $availableTimeSlots = $doctor->available_time_slots ?? ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
            $time = $availableTimeSlots[array_rand($availableTimeSlots)];

            // Payment status and fee paid
            $paymentStatus = ($status === 'completed') ? 'paid' : (rand(0, 1) ? 'pending' : 'paid');
            $feePaid = ($paymentStatus === 'paid') ? $doctor->consultation_fee : 0;

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'appointment_date' => $date->format('Y-m-d'),
                'appointment_time' => $time,
                'status' => $status,
                'reason' => $this->getRandomReason(),
                'notes' => ($status === 'completed') ? $this->getRandomNotes() : null,
                'fee_paid' => $feePaid,
                'payment_status' => $paymentStatus,
            ]);
        }
    }

    /**
     * Get a random index based on probability weights.
     */
    private function getRandomIndexByProbability(array $probabilities): int
    {
        $total = array_sum($probabilities);
        $rand = rand(1, $total);

        $sum = 0;
        foreach ($probabilities as $index => $weight) {
            $sum += $weight;
            if ($rand <= $sum) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Get a random reason for appointment.
     */
    private function getRandomReason(): string
    {
        $reasons = [
            'Regular check-up',
            'Fever and cold symptoms',
            'Persistent headache',
            'Back pain',
            'Skin rash',
            'Digestive issues',
            'Joint pain',
            'Eye examination',
            'Ear infection',
            'Respiratory problems',
            'Follow-up appointment',
            'Prescription renewal',
            'Annual physical examination',
            'Consultation for chronic condition',
            'Preventive health screening',
        ];

        return $reasons[array_rand($reasons)];
    }

    /**
     * Get random doctor notes.
     */
    private function getRandomNotes(): ?string
    {
        $notes = [
            'Patient is recovering well. Prescribed medication for 7 days.',
            'Recommended rest and hydration. Follow-up in 2 weeks if symptoms persist.',
            'Referred to specialist for further examination.',
            'Prescribed antibiotics. Patient should complete the full course.',
            'Advised lifestyle changes including diet and exercise.',
            'Symptoms have improved since last visit. Continue current treatment.',
            'Ordered blood tests. Results to be discussed in next appointment.',
            'Patient showing good progress. No change in medication needed.',
            'Discussed treatment options. Patient opted for conservative approach.',
            'Recommended physical therapy sessions twice a week.',
        ];

        return $notes[array_rand($notes)];
    }
}
