<?php

namespace Database\Seeders;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@jajalmedical.com'],
            [
                'name' => 'Medical Administrator',
                'password' => Hash::make('Password123!'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Viewer User
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@jajalmedical.com'],
            [
                'name' => 'Clinical Viewer',
                'password' => Hash::make('Password123!'),
                'role' => User::ROLE_VIEWER,
                'email_verified_at' => now(),
            ]
        );

        // 3. Seed Engineer User (Bonus Role)
        $engineer = User::firstOrCreate(
            ['email' => 'engineer@jajalmedical.com'],
            [
                'name' => 'Biomedical Engineer',
                'password' => Hash::make('Password123!'),
                'role' => User::ROLE_ENGINEER,
                'email_verified_at' => now(),
            ]
        );

        // 4. Seed at least 25 realistic dummy medical cases if count < 25
        $caseTemplates = [
            [
                'case_number' => 'JM-100201',
                'patient_reference' => 'DUMMY-CR-1042',
                'surgeon_name' => 'Dr. Robert Chen, MD',
                'implant_type' => 'Titanium Cranial Plate',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(5)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100202',
                'patient_reference' => 'DUMMY-MD-2091',
                'surgeon_name' => 'Dr. Sarah Al-Mansoor, FRCS',
                'implant_type' => 'Patient-Specific Mandibular Reconstruction Plate',
                'status' => 'In Review',
                'priority' => 'High',
                'surgery_date' => now()->addDays(8)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100203',
                'patient_reference' => 'DUMMY-KN-3104',
                'surgeon_name' => 'Dr. Jonathan Patel, MBBS',
                'implant_type' => 'Bicondylar Femoral Knee Component',
                'status' => 'Approved',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(14)->format('Y-m-d'),
                'created_by' => $engineer->id,
            ],
            [
                'case_number' => 'JM-100204',
                'patient_reference' => 'DUMMY-HP-4412',
                'surgeon_name' => 'Dr. Michael Sterling, FACS',
                'implant_type' => 'Total Hip Arthroplasty (THA) Modular Stem',
                'status' => 'Completed',
                'priority' => 'Low',
                'surgery_date' => now()->subDays(10)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100205',
                'patient_reference' => 'DUMMY-SP-5021',
                'surgeon_name' => 'Dr. Aris Thorne, MD',
                'implant_type' => 'Thoracolumbar Spinal Fusion Cage',
                'status' => 'Draft',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(20)->format('Y-m-d'),
                'created_by' => $engineer->id,
            ],
            [
                'case_number' => 'JM-100206',
                'patient_reference' => 'DUMMY-PK-6190',
                'surgeon_name' => 'Dr. Elena Rostova, MD',
                'implant_type' => 'Custom PEEK Cranial Implant',
                'status' => 'Planning',
                'priority' => 'High',
                'surgery_date' => now()->addDays(12)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100207',
                'patient_reference' => 'DUMMY-MX-7201',
                'surgeon_name' => 'Dr. Emily Watson, MD',
                'implant_type' => 'Maxillofacial Contour Mesh',
                'status' => 'In Review',
                'priority' => 'Medium',
                'surgery_date' => now()->addDays(15)->format('Y-m-d'),
                'created_by' => $engineer->id,
            ],
            [
                'case_number' => 'JM-100208',
                'patient_reference' => 'DUMMY-AC-8302',
                'surgeon_name' => 'Dr. David Okafor, MD',
                'implant_type' => 'Acetabular Cup & Porous Augment',
                'status' => 'Approved',
                'priority' => 'High',
                'surgery_date' => now()->addDays(6)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100209',
                'patient_reference' => 'DUMMY-OR-9411',
                'surgeon_name' => 'Dr. Meera Nambiar, MS (Ortho)',
                'implant_type' => 'Custom 3D-Printed Orbital Floor Mesh',
                'status' => 'Cancelled',
                'priority' => 'Low',
                'surgery_date' => null,
                'created_by' => $admin->id,
            ],
            [
                'case_number' => 'JM-100210',
                'patient_reference' => 'DUMMY-HM-1033',
                'surgeon_name' => 'Dr. Robert Chen, MD',
                'implant_type' => 'Proximal Humeral Fracture Plate',
                'status' => 'Completed',
                'priority' => 'Medium',
                'surgery_date' => now()->subDays(3)->format('Y-m-d'),
                'created_by' => $admin->id,
            ],
        ];

        foreach ($caseTemplates as $template) {
            MedicalCase::firstOrCreate(
                ['case_number' => $template['case_number']],
                $template
            );
        }

        // Generate additional cases via factory to exceed 20 cases comfortably (e.g. 25 total)
        $currentCount = MedicalCase::count();
        if ($currentCount < 25) {
            $needed = 25 - $currentCount;
            MedicalCase::factory()->count($needed)->create([
                'created_by' => $admin->id,
            ]);
        }
    }
}
