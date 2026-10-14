<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CaseApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Requirement: A guest cannot access protected APIs (returns HTTP 401).
     */
    public function test_guest_cannot_access_protected_apis(): void
    {
        $response = $this->getJson('/api/cases');
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);

        $postResponse = $this->postJson('/api/cases', []);
        $postResponse->assertStatus(401);
    }

    /**
     * Requirement: Authenticated Viewer can list cases with pagination.
     */
    public function test_authenticated_viewer_can_list_cases(): void
    {
        $viewer = User::factory()->viewer()->create();
        Sanctum::actingAs($viewer);

        MedicalCase::factory()->count(15)->create();

        $response = $this->getJson('/api/cases');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cases retrieved successfully',
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'case_number',
                        'patient_reference',
                        'surgeon_name',
                        'implant_type',
                        'status',
                        'priority',
                        'surgery_date',
                        'created_by',
                    ],
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    /**
     * Requirement: Authenticated Viewer can view single case.
     */
    public function test_authenticated_viewer_can_view_single_case(): void
    {
        $viewer = User::factory()->viewer()->create();
        Sanctum::actingAs($viewer);

        $case = MedicalCase::factory()->create([
            'case_number' => 'JM-API-VIEW-01',
        ]);

        $response = $this->getJson("/api/cases/{$case->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'case_number' => 'JM-API-VIEW-01',
                ],
            ]);
    }

    /**
     * Requirement: An Admin can create a case via API (returns 201).
     */
    public function test_admin_can_create_case_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $payload = [
            'case_number' => 'JM-API-CREATE-001',
            'patient_reference' => 'DUMMY-API-001',
            'surgeon_name' => 'Dr. Robert Chen, MD',
            'implant_type' => 'Titanium Cranial Plate',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2026-11-15',
        ];

        $response = $this->postJson('/api/cases', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Case created successfully',
                'data' => [
                    'case_number' => 'JM-API-CREATE-001',
                    'patient_reference' => 'DUMMY-API-001',
                    'created_by' => $admin->id,
                ],
            ]);

        $this->assertDatabaseHas('cases', [
            'case_number' => 'JM-API-CREATE-001',
            'created_by' => $admin->id,
        ]);
    }

    /**
     * Requirement: Creating a case with a duplicate case number fails with HTTP 422 for the API.
     */
    public function test_creating_case_with_duplicate_case_number_fails_with_422(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        MedicalCase::factory()->create([
            'case_number' => 'JM-DUPLICATE-99',
        ]);

        $payload = [
            'case_number' => 'JM-DUPLICATE-99',
            'patient_reference' => 'DUMMY-DUP-01',
            'surgeon_name' => 'Dr. Sarah Al-Mansoor',
            'implant_type' => 'Custom PEEK Cranial Implant',
            'status' => 'Planning',
            'priority' => 'High',
        ];

        $response = $this->postJson('/api/cases', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['case_number']);
    }

    /**
     * Requirement: Invalid status and priority values are rejected with HTTP 422.
     */
    public function test_invalid_status_and_priority_are_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $payload = [
            'case_number' => 'JM-INVALID-STATUS',
            'patient_reference' => 'DUMMY-INV-01',
            'surgeon_name' => 'Dr. Sarah Al-Mansoor',
            'implant_type' => 'Cranial Plate',
            'status' => 'NonExistentStatus',
            'priority' => 'UrgentDanger',
        ];

        $response = $this->postJson('/api/cases', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status', 'priority']);
    }

    /**
     * Requirement: A Viewer cannot create a case and receives HTTP 403.
     */
    public function test_viewer_cannot_create_case_via_api(): void
    {
        $viewer = User::factory()->viewer()->create();
        Sanctum::actingAs($viewer);

        $payload = [
            'case_number' => 'JM-VIEWER-NO-CREATE',
            'patient_reference' => 'DUMMY-NO',
            'surgeon_name' => 'Dr. Nobody',
            'implant_type' => 'Plate',
            'status' => 'Draft',
            'priority' => 'Low',
        ];

        $response = $this->postJson('/api/cases', $payload);

        $response->assertStatus(403);
    }

    /**
     * Requirement: A Viewer cannot update a case and receives HTTP 403.
     */
    public function test_viewer_cannot_update_case_via_api(): void
    {
        $viewer = User::factory()->viewer()->create();
        Sanctum::actingAs($viewer);

        $case = MedicalCase::factory()->create();

        $response = $this->putJson("/api/cases/{$case->id}", [
            'case_number' => $case->case_number,
            'patient_reference' => 'HACKED-REF',
            'surgeon_name' => 'Dr. Hacker',
            'implant_type' => 'Mesh',
            'status' => 'Completed',
            'priority' => 'High',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Requirement: A Viewer cannot delete a case and receives HTTP 403.
     */
    public function test_viewer_cannot_delete_case_via_api(): void
    {
        $viewer = User::factory()->viewer()->create();
        Sanctum::actingAs($viewer);

        $case = MedicalCase::factory()->create();

        $response = $this->deleteJson("/api/cases/{$case->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('cases', ['id' => $case->id]);
    }

    /**
     * Requirement: Admin can update and delete case via API.
     */
    public function test_admin_can_update_and_delete_case_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $case = MedicalCase::factory()->create([
            'case_number' => 'JM-API-EDIT-01',
            'status' => 'Draft',
        ]);

        // Update
        $updateResponse = $this->putJson("/api/cases/{$case->id}", [
            'case_number' => 'JM-API-EDIT-01',
            'patient_reference' => 'DUMMY-UPDATED-API',
            'surgeon_name' => 'Dr. Jonathan Patel',
            'implant_type' => 'Spinal Fusion Cage',
            'status' => 'Approved',
            'priority' => 'Medium',
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'Approved',
                ],
            ]);

        // Delete
        $deleteResponse = $this->deleteJson("/api/cases/{$case->id}");
        $deleteResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Case deleted successfully',
            ]);

        $this->assertDatabaseMissing('cases', ['id' => $case->id]);
    }

    /**
     * Requirement: Nonexistent case returns HTTP 404.
     */
    public function test_nonexistent_case_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/cases/999999');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Case not found',
            ]);
    }
}
