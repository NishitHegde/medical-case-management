<?php

namespace Database\Factories;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCase>
 */
class MedicalCaseFactory extends Factory
{
    /**
     * The model that the factory applies to.
     */
    protected $model = MedicalCase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $implantTypes = [
            'Titanium Cranial Plate',
            'Custom PEEK Cranial Implant',
            'Patient-Specific Mandibular Reconstruction Plate',
            'Total Hip Arthroplasty (THA) Modular Stem',
            'Bicondylar Femoral Knee Component',
            'Thoracolumbar Spinal Fusion Cage',
            'Maxillofacial Contour Mesh',
            'Acetabular Cup & Porous Augment',
            'Custom 3D-Printed Orbital Floor Mesh',
            'Subperiosteal Jaw Implant',
            'Proximal Humeral Fracture Plate',
            'Custom Osteotomy Guide & Plate',
        ];

        $surgeons = [
            'Dr. Robert Chen, MD',
            'Dr. Sarah Al-Mansoor, FRCS',
            'Dr. Emily Watson, MD',
            'Dr. Jonathan Patel, MBBS',
            'Dr. Aris Thorne, MD',
            'Dr. Michael Sterling, FACS',
            'Dr. Elena Rostova, MD',
            'Dr. David Okafor, MD',
            'Dr. Meera Nambiar, MS (Ortho)',
        ];

        return [
            'case_number' => 'JM-' . fake()->unique()->numberBetween(100000, 999999),
            'patient_reference' => 'DUMMY-' . strtoupper(fake()->bothify('??-####')),
            'surgeon_name' => fake()->randomElement($surgeons),
            'implant_type' => fake()->randomElement($implantTypes),
            'status' => fake()->randomElement(MedicalCase::STATUSES),
            'priority' => fake()->randomElement(MedicalCase::PRIORITIES),
            'surgery_date' => fake()->optional(0.85)->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
            'created_by' => User::factory(),
        ];
    }

    /**
     * State for specific status.
     */
    public function status(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * State for specific priority.
     */
    public function priority(string $priority): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $priority,
        ]);
    }
}
