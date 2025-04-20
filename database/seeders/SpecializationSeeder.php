<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'Cardiology',
                'description' => 'Deals with disorders of the heart and the cardiovascular system.',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Focuses on the diagnosis and treatment of skin disorders.',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Deals with disorders of the nervous system, including the brain and spinal cord.',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Focuses on the diagnosis and treatment of disorders of the bones, joints, ligaments, tendons, and muscles.',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Deals with the medical care of infants, children, and adolescents.',
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Focuses on the diagnosis, prevention, and treatment of mental disorders.',
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Deals with the diagnosis and treatment of eye disorders.',
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Focuses on the health of the female reproductive system.',
            ],
        ];

        foreach ($specializations as $specialization) {
            Specialization::create($specialization);
        }
    }
}
