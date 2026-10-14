<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Requirement: A guest cannot access protected case pages (redirects to login).
     */
    public function test_guest_cannot_access_protected_pages(): void
    {
        $response = $this->get('/cases');
        $response->assertRedirect('/login');

        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Requirement: Viewer can view cases list and search/filter.
     */
    public function test_viewer_can_list_and_search_cases(): void
    {
        $viewer = User::factory()->viewer()->create();
        $caseA = MedicalCase::factory()->create([
            'case_number' => 'JM-SEARCH-001',
            'surgeon_name' => 'Dr. Alpha Surgeon',
            'status' => 'Planning',
        ]);
        $caseB = MedicalCase::factory()->create([
            'case_number' => 'JM-SEARCH-002',
            'surgeon_name' => 'Dr. Beta Surgeon',
            'status' => 'Completed',
        ]);

        // General list
        $response = $this->actingAs($viewer)->get('/cases');
        $response->assertStatus(200);
        $response->assertSee('JM-SEARCH-001');
        $response->assertSee('JM-SEARCH-002');

        // Search by case number
        $searchResponse = $this->actingAs($viewer)->get('/cases?case_number=001');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('JM-SEARCH-001');
        $searchResponse->assertDontSee('JM-SEARCH-002');

        // Filter by status
        $filterResponse = $this->actingAs($viewer)->get('/cases?status=Completed');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('JM-SEARCH-002');
        $filterResponse->assertDontSee('JM-SEARCH-001');
    }

    /**
     * Requirement: Viewer can open case details.
     */
    public function test_viewer_can_view_case_details(): void
    {
        $viewer = User::factory()->viewer()->create();
        $case = MedicalCase::factory()->create([
            'case_number' => 'JM-VIEW-999',
            'patient_reference' => 'DUMMY-REF-999',
        ]);

        $response = $this->actingAs($viewer)->get("/cases/{$case->id}");
        $response->assertStatus(200);
        $response->assertSee('JM-VIEW-999');
        $response->assertSee('DUMMY-REF-999');
    }

    /**
     * Requirement: Viewer cannot create cases and receives HTTP 403.
     */
    public function test_viewer_cannot_access_create_form_or_store(): void
    {
        $viewer = User::factory()->viewer()->create();

        // GET create form
        $response = $this->actingAs($viewer)->get('/cases/create');
        $response->assertStatus(403);

        // POST store
        $storeResponse = $this->actingAs($viewer)->post('/cases', [
            'case_number' => 'JM-FORBIDDEN-01',
            'patient_reference' => 'DUMMY-FORBIDDEN',
            'surgeon_name' => 'Dr. Evil',
            'implant_type' => 'Plate',
            'status' => 'Draft',
            'priority' => 'Low',
        ]);
        $storeResponse->assertStatus(403);
    }

    /**
     * Requirement: Viewer cannot edit or delete cases and receives HTTP 403.
     */
    public function test_viewer_cannot_edit_or_delete_case(): void
    {
        $viewer = User::factory()->viewer()->create();
        $case = MedicalCase::factory()->create();

        // GET edit
        $response = $this->actingAs($viewer)->get("/cases/{$case->id}/edit");
        $response->assertStatus(403);

        // PUT update
        $updateResponse = $this->actingAs($viewer)->put("/cases/{$case->id}", [
            'case_number' => $case->case_number,
            'patient_reference' => 'UPDATED-REF',
            'surgeon_name' => 'Dr. Updated',
            'implant_type' => 'Updated Plate',
            'status' => 'Approved',
            'priority' => 'High',
        ]);
        $updateResponse->assertStatus(403);

        // DELETE
        $deleteResponse = $this->actingAs($viewer)->delete("/cases/{$case->id}");
        $deleteResponse->assertStatus(403);
    }

    /**
     * Requirement: Admin can create a case and created_by is assigned to the authenticated user.
     */
    public function test_admin_can_create_case(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/cases', [
            'case_number' => 'JM-ADMIN-100',
            'patient_reference' => 'DUMMY-ADM-01',
            'surgeon_name' => 'Dr. Robert Chen, MD',
            'implant_type' => 'Titanium Cranial Plate',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2026-11-20',
        ]);

        $response->assertRedirect('/cases');
        $this->assertDatabaseHas('cases', [
            'case_number' => 'JM-ADMIN-100',
            'patient_reference' => 'DUMMY-ADM-01',
            'created_by' => $admin->id,
        ]);
    }

    /**
     * Requirement: Admin can update and delete a case.
     */
    public function test_admin_can_update_and_delete_case(): void
    {
        $admin = User::factory()->admin()->create();
        $case = MedicalCase::factory()->create([
            'case_number' => 'JM-UPDATE-ME',
            'created_by' => $admin->id,
        ]);

        // Update
        $updateResponse = $this->actingAs($admin)->put("/cases/{$case->id}", [
            'case_number' => 'JM-UPDATE-ME',
            'patient_reference' => 'DUMMY-UPDATED',
            'surgeon_name' => 'Dr. Sarah Al-Mansoor',
            'implant_type' => 'Custom PEEK Cranial Implant',
            'status' => 'Approved',
            'priority' => 'High',
            'surgery_date' => '2026-12-01',
        ]);

        $updateResponse->assertRedirect("/cases/{$case->id}");
        $this->assertDatabaseHas('cases', [
            'id' => $case->id,
            'status' => 'Approved',
            'patient_reference' => 'DUMMY-UPDATED',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete("/cases/{$case->id}");
        $deleteResponse->assertRedirect('/cases');
        $this->assertDatabaseMissing('cases', [
            'id' => $case->id,
        ]);
    }
}
