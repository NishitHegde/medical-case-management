<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EngineerRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bonus: Engineer role can create a case.
     */
    public function test_engineer_can_create_case_web_and_api(): void
    {
        $engineer = User::factory()->engineer()->create();

        // Web create
        $webResponse = $this->actingAs($engineer)->post('/cases', [
            'case_number' => 'JM-ENG-001',
            'patient_reference' => 'DUMMY-ENG-01',
            'surgeon_name' => 'Dr. Engineer Test',
            'implant_type' => 'Custom Mandibular Plate',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2026-11-20',
        ]);

        $webResponse->assertRedirect('/cases');
        $this->assertDatabaseHas('cases', [
            'case_number' => 'JM-ENG-001',
            'created_by' => $engineer->id,
        ]);

        // API create
        Sanctum::actingAs($engineer);
        $apiResponse = $this->postJson('/api/cases', [
            'case_number' => 'JM-ENG-002',
            'patient_reference' => 'DUMMY-ENG-02',
            'surgeon_name' => 'Dr. Engineer Test 2',
            'implant_type' => 'Titanium Mesh',
            'status' => 'In Review',
            'priority' => 'Medium',
        ]);

        $apiResponse->assertStatus(201);
    }

    /**
     * Bonus: Engineer role can update a case.
     */
    public function test_engineer_can_update_case(): void
    {
        $engineer = User::factory()->engineer()->create();
        $case = MedicalCase::factory()->create();

        $response = $this->actingAs($engineer)->put("/cases/{$case->id}", [
            'case_number' => $case->case_number,
            'patient_reference' => 'UPDATED-BY-ENG',
            'surgeon_name' => 'Dr. Engineer Updated',
            'implant_type' => 'PEEK Plate',
            'status' => 'Approved',
            'priority' => 'Low',
        ]);

        $response->assertRedirect("/cases/{$case->id}");
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
            'patient_reference' => 'UPDATED-BY-ENG',
            'status' => 'Approved',
        ]);
    }

    /**
     * Bonus: Engineer role CANNOT delete a case and receives HTTP 403.
     */
    public function test_engineer_cannot_delete_case(): void
    {
        $engineer = User::factory()->engineer()->create();
        $case = MedicalCase::factory()->create();

        // Web delete
        $response = $this->actingAs($engineer)->delete("/cases/{$case->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('cases', ['id' => $case->id]);

        // API delete
        Sanctum::actingAs($engineer);
        $apiResponse = $this->deleteJson("/api/cases/{$case->id}");
        $apiResponse->assertStatus(403);
        $this->assertDatabaseHas('cases', ['id' => $case->id]);
    }
}
