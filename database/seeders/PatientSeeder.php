<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientUsers = User::where('role', 'patient')->get();
        $genders = ['male', 'female', 'other'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($patientUsers as $user) {
            $gender = $genders[array_rand($genders)];
            $bloodGroup = $bloodGroups[array_rand($bloodGroups)];

            // Generate a random date of birth between 18 and 70 years ago
            $years = rand(18, 70);
            $dateOfBirth = Carbon::now()->subYears($years)->subDays(rand(0, 365));

            Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => $dateOfBirth,
                'gender' => $gender,
                'blood_group' => $bloodGroup,
                'allergies' => $this->getRandomAllergies(),
                'medical_history' => $this->getRandomMedicalHistory(),
            ]);
        }
    }

    /**
     * Get random allergies.
     */
    private function getRandomAllergies(): ?string
    {
        $allergies = [
            null,
            'Peanuts',
            'Penicillin',
            'Dust',
            'Pollen',
            'Latex',
            'Shellfish',
            'Eggs',
            'Milk',
            'Soy',
        ];

        // 30% chance of having no allergies
        if (rand(1, 10) <= 3) {
            return null;
        }

        // 1-3 allergies
        $numAllergies = rand(1, 3);
        $selectedAllergies = [];

        for ($i = 0; $i < $numAllergies; $i++) {
            $allergy = $allergies[array_rand($allergies)];
            if ($allergy !== null && !in_array($allergy, $selectedAllergies)) {
                $selectedAllergies[] = $allergy;
            }
        }

        return !empty($selectedAllergies) ? implode(', ', $selectedAllergies) : null;
    }

    /**
     * Get random medical history.
     */
    private function getRandomMedicalHistory(): ?string
    {
        $conditions = [
            null,
            'Hypertension',
            'Diabetes Type 2',
            'Asthma',
            'Arthritis',
            'Migraine',
            'Hypothyroidism',
            'Depression',
            'Anxiety Disorder',
            'GERD',
            'Hypercholesterolemia',
        ];

        // 40% chance of having no medical history
        if (rand(1, 10) <= 4) {
            return null;
        }

        // 1-2 conditions
        $numConditions = rand(1, 2);
        $selectedConditions = [];

        for ($i = 0; $i < $numConditions; $i++) {
            $condition = $conditions[array_rand($conditions)];
            if ($condition !== null && !in_array($condition, $selectedConditions)) {
                $selectedConditions[] = $condition;
            }
        }

        return !empty($selectedConditions) ? implode(', ', $selectedConditions) : null;
    }
}
