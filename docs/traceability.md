# Requirements Traceability Matrix (RTM)

This matrix maps user requirements to their architectural implementation (Controllers, Models, Policies, Form Requests, Views) and the automated tests verifying them.

---

## Traceability Summary Table

| Req ID | Requirement Description | Implementation Components | Automated Verification Tests | Status |
|---|---|---|---|---|
| **REQ-001** | Admin can create a case | `App\Http\Controllers\Web\CaseController@store`<br>`App\Http\Controllers\Api\CaseApiController@store`<br>`App\Policies\CasePolicy@create` | `Tests\Feature\CaseWebTest::test_admin_can_create_case`<br>`Tests\Feature\CaseApiTest::test_admin_can_create_case_via_api` | **Verified** |
| **REQ-002** | Case Number must be unique | `database/migrations/*_create_cases_table.php`<br>`App\Http\Requests\StoreCaseRequest`<br>`App\Http\Requests\UpdateCaseRequest` | `Tests\Feature\CaseApiTest::test_creating_case_with_duplicate_case_number_fails_with_422` | **Verified** |
| **REQ-003** | Only Admin can delete cases | `App\Http\Controllers\Web\CaseController@destroy`<br>`App\Http\Controllers\Api\CaseApiController@destroy`<br>`App\Policies\CasePolicy@delete` | `Tests\Feature\CaseWebTest::test_viewer_cannot_edit_or_delete_case`<br>`Tests\Feature\CaseApiTest::test_viewer_cannot_delete_case_via_api`<br>`Tests\Feature\EngineerRoleTest::test_engineer_cannot_delete_case` | **Verified** |
| **REQ-004** | Viewer can search, filter, and view cases but cannot mutate data | `App\Policies\CasePolicy@viewAny`<br>`App\Policies\CasePolicy@view`<br>`App\Http\Controllers\Web\CaseController@index` | `Tests\Feature\CaseWebTest::test_viewer_can_list_and_search_cases`<br>`Tests\Feature\CaseWebTest::test_viewer_can_view_case_details`<br>`Tests\Feature\CaseWebTest::test_viewer_cannot_access_create_form_or_store` | **Verified** |
| **REQ-005** | Guest requests redirected or denied with 401 | `routes/web.php` (`auth` middleware)<br>`routes/api.php` (`auth:sanctum` middleware)<br>`App\Exceptions\Handler@register` | `Tests\Feature\CaseWebTest::test_guest_cannot_access_protected_pages`<br>`Tests\Feature\CaseApiTest::test_guest_cannot_access_protected_apis` | **Verified** |
| **REQ-006** | REST API token authentication via Laravel Sanctum | `App\Http\Controllers\Api\AuthController@login`<br>`App\Http\Controllers\Api\AuthController@logout` | `Tests\Feature\AuthTest::test_valid_api_login_returns_token`<br>`Tests\Feature\AuthTest::test_invalid_api_login_returns_401`<br>`Tests\Feature\AuthTest::test_api_logout_revokes_token` | **Verified** |
| **REQ-007** | Strict domain validation on Status and Priority | `App\Models\MedicalCase::STATUSES`<br>`App\Models\MedicalCase::PRIORITIES`<br>`App\Http\Requests\StoreCaseRequest` | `Tests\Feature\CaseApiTest::test_invalid_status_and_priority_are_rejected` | **Verified** |
| **REQ-008** | Automatic assignment of `created_by` | `CaseController@store` (`$validated['created_by'] = $request->user()->id;`) | `Tests\Feature\CaseWebTest::test_admin_can_create_case`<br>`Tests\Feature\CaseApiTest::test_admin_can_create_case_via_api` | **Verified** |

---

## Detailed Requirement Analysis

### REQ-001: Admin Can Create a Case
- **Specification**: Authenticated administrators must be able to log a new patient-specific medical case. The system must automatically bind the `created_by` field to the administrator's ID and persist all required clinical specifications.
- **Implementation**:
  - `App\Http\Controllers\Web\CaseController@create` & `@store`
  - `App\Http\Controllers\Api\CaseApiController@store`
  - `App\Policies\CasePolicy::create()` verifies that `$user->isAdmin() || $user->isEngineer()`
  - `StoreCaseRequest::authorize()` runs authorization before entering the controller
- **Automated Tests**:
  - `CaseWebTest::test_admin_can_create_case()`: Submits case data and asserts redirection with database presence: `assertDatabaseHas('cases', ['created_by' => $admin->id])`.
  - `CaseApiTest::test_admin_can_create_case_via_api()`: Issues `POST /api/cases` and validates HTTP 201 with standard JSON envelope and `CaseResource`.

---

### REQ-002: Case Number Must Be Unique
- **Specification**: Case numbers serve as immutable clinical and regulatory tracking IDs for patient-specific implants. Duplicate case numbers must be strictly prevented at both the request validation level and the database schema level.
- **Implementation**:
  - Database schema: `$table->string('case_number')->unique()` in `2026_10_14_000000_create_cases_table.php`.
  - `StoreCaseRequest`: `'case_number' => ['required', 'string', 'max:50', 'unique:cases,case_number']`.
  - `UpdateCaseRequest`: `Rule::unique('cases', 'case_number')->ignore($caseId)`.
- **Automated Tests**:
  - `CaseApiTest::test_creating_case_with_duplicate_case_number_fails_with_422()`: Pre-creates a case and then attempts to submit a new case with the identical `case_number`. Asserts HTTP status 422 with a validation error on `case_number`.

---

### REQ-003: Only Admin Can Delete Cases
- **Specification**: Deleting clinical records permanently purges surgical history. Only users with the `admin` role may perform deletions. Viewers and Engineers must be rejected on both web and API endpoints with HTTP 403 Forbidden.
- **Implementation**:
  - `App\Policies\CasePolicy::delete()`: Returns `$user->isAdmin()`.
  - `CaseController@destroy()` & `CaseApiController@destroy()`: Calls `$this->authorize('delete', $case)`.
  - UI blade template: `@can('delete', $case)` conditionally hides the delete button.
- **Automated Tests**:
  - `CaseWebTest::test_viewer_cannot_edit_or_delete_case()`: Asserts that a Viewer sending a `DELETE /cases/{id}` request receives HTTP 403.
  - `CaseApiTest::test_viewer_cannot_delete_case_via_api()`: Asserts `DELETE /api/cases/{id}` returns HTTP 403 and the record remains in the database.
  - `EngineerRoleTest::test_engineer_cannot_delete_case()`: Asserts that even an Engineer with creation privileges cannot delete a case (receives HTTP 403).
