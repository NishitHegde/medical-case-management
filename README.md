# Medical Case Management System

> **Jajal Medical &mdash; Software Developer Intern Practical Assignment**  
> A secure, full-stack Laravel web application and RESTful API for managing and tracking patient-specific medical implant manufacturing workflows from initial design draft to post-operative completion.

---

## 1. Overview & Problem Statement

### Context & Objectives
Patient-specific medical implants (cranial reconstruction plates, custom mandibular prostheses, orthopaedic knee/hip stems, and spinal cages) require precise lifecycle coordination between biomedical design engineers, attending surgical specialists, and manufacturing technicians. 

The **Medical Case Management System** provides a centralized, HIPAA-informed internal clinical tracking portal that:
- Coordinates case progression across defined clinical workflow phases: `Draft`, `Planning`, `In Review`, `Approved`, `Completed`, and `Cancelled`.
- Enforces strict server-side Role-Based Access Control (RBAC) via Laravel Policies.
- Guarantees medical data integrity by preventing duplicate case identifiers at both database and validation layers.
- Exposes a token-authenticated JSON REST API powered by Laravel Sanctum.
- Integrates with external Public Holiday calendars to prevent surgical scheduling conflicts.
- Adheres exclusively to synthetic, de-identified dummy data without exposing protected health information (PHI).

---

## 2. Technology Stack

- **Backend Framework**: PHP 8.2 &bull; Laravel 10.50.3
- **Database**: MySQL / MariaDB (InnoDB, UTF-8 MB4) &bull; SQLite (in-memory for instant, isolated automated testing)
- **Frontend**: Laravel Blade &bull; HTML5 &bull; CSS3 &bull; Vanilla JavaScript &bull; Bootstrap 5.3 &bull; Bootstrap Icons
- **Authentication**: Laravel Session Auth (Web) &bull; Laravel Sanctum Personal Access Tokens (REST API)
- **Authorization**: Laravel Policies (`App\Policies\CasePolicy`) &bull; Form Request Authorization
- **Validation**: Custom Form Request classes (`StoreCaseRequest`, `UpdateCaseRequest`)
- **Automated Testing**: PHPUnit 10 (28 automated Feature & Unit tests, 195 assertions)
- **External Integration**: Public Holiday API (via Laravel HTTP Client with timeout & fallback)
- **Version Control**: Git

---

## 3. User Roles & Access Control Matrix

The system implements strict server-side authorization. Hiding UI elements is never treated as the primary security layer.

| Operation / Feature | Admin | Viewer | Engineer (Bonus Role) | Guest |
|---|:---:|:---:|:---:|:---:|
| **Web & API Login / Logout** | Yes | Yes | Yes | Login Only |
| **View Dashboard & Metrics** | Yes | Yes | Yes | No (Redirects to Login / 401) |
| **Search & Filter Cases** | Yes | Yes | Yes | No (Redirects to Login / 401) |
| **Inspect Full Case Details** | Yes | Yes | Yes | No (Redirects to Login / 401) |
| **Create New Cases** | Yes | No (HTTP 403) | Yes | No (Redirects to Login / 401) |
| **Edit / Update Existing Cases** | Yes | No (HTTP 403) | Yes | No (Redirects to Login / 401) |
| **Change Case Workflow Status** | Yes | No (HTTP 403) | Yes | No (Redirects to Login / 401) |
| **Delete Cases** | Yes | No (HTTP 403) | No (HTTP 403) | No (Redirects to Login / 401) |
| **Sanctum API Endpoints** | Yes | Read-Only | Read/Write (No Delete) | Public Login Only |

---

## 4. Pre-Configured Demo Credentials

The database seeder automatically initializes realistic accounts and dummy data:

| Role | Email Address | Password | Permissions Scope |
|---|---|---|---|
| **Administrator** | `admin@jajalmedical.com` | `Password123!` | Full permissions: Create, View, Edit, Status, Delete |
| **Clinical Viewer** | `viewer@jajalmedical.com` | `Password123!` | Read-only access: View dashboard, list, search, filter, details |
| **Biomedical Engineer** | `engineer@jajalmedical.com` | `Password123!` | Authoring access: Create, edit, update status. Deletion restricted. |

> **Note**: A convenient **"Demo Credentials"** one-click quick-fill box is provided directly on the web login screen for evaluation and testing.

---

## 5. Prerequisites

Before installing, ensure your environment has:
- **PHP** &gt;= 8.2 with extensions: `pdo_mysql`, `pdo_sqlite`, `sqlite3`, `curl`, `mbstring`, `openssl`, `zip`, `fileinfo`
- **Composer** &gt;= 2.2
- **MySQL** &gt;= 8.0 or **MariaDB** &gt;= 10.4
- **Node.js** &gt;= 18 (optional for production asset bundling; application uses CDN-powered Bootstrap 5)

---

## 6. Installation & Setup Guide

### Step 1: Clone the Repository
```bash
git clone https://github.com/<your-username>/medical-case-management.git
cd medical-case-management
```

### Step 2: Install Composer Dependencies
```bash
composer install
```

### Step 3: Configure Environment
Copy the example environment configuration:
```bash
cp .env.example .env
```

Generate an application encryption key:
```bash
php artisan key:generate
```

Configure your database credentials in `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307        # Or 3306 according to your MySQL server configuration
DB_DATABASE=medical_cases
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations and Seed Dummy Data
Create the target MySQL database (e.g. `medical_cases`), then run:
```bash
php artisan migrate:fresh --seed
```
This migration creates:
- `users` table with RBAC `role`
- `cases` table with indexes and constraints
- `personal_access_tokens` table for Laravel Sanctum
- Seeds the **Admin**, **Viewer**, and **Engineer** accounts
- Generates **25 realistic dummy medical cases** distributed across implant categories, surgeons, and statuses.

### Step 5: Start the Development Server
```bash
php artisan serve --port=8000
```
Open your browser and navigate to:  
&rarr; **[http://localhost:8000](http://localhost:8000)**

---

## 7. Running Automated Tests

The test suite leverages an **isolated, in-memory SQLite database** configured in `phpunit.xml`. Running the tests is instantaneous and completely non-destructive to your development database.

Execute all automated feature and unit tests:
```bash
php artisan test
```

### Verified Test Coverage (28 Passed Tests, 195 Assertions)
- **Authentication (`tests/Feature/AuthTest.php`)**:
  - Valid web login succeeds and redirects to dashboard.
  - Invalid web login is rejected with validation error.
  - Web logout invalidates session and redirects to login.
  - Valid API login returns Sanctum bearer token (HTTP 200).
  - Invalid API login credentials return HTTP 401.
  - Authenticated API logout revokes token.
- **Web Authorization & CRUD (`tests/Feature/CaseWebTest.php`)**:
  - Unauthenticated guests are redirected to login for protected pages.
  - Viewers can browse, paginate, and search cases by case number and surgeon.
  - Viewers can inspect full case details.
  - Viewers cannot access creation or edit forms (HTTP 403).
  - Viewers cannot delete cases (HTTP 403).
  - Administrators can create cases (verifies automatic `created_by` assignment).
  - Administrators can update and delete cases.
- **REST API Endpoints (`tests/Feature/CaseApiTest.php`)**:
  - Unauthenticated API calls return HTTP 401.
  - Viewers can list cases with pagination metadata.
  - Viewers can fetch single case resource.
  - Viewers cannot create, update, or delete cases via API (HTTP 403).
  - Administrators can create cases via API (HTTP 201 with `CaseResource`).
  - Duplicate case numbers are rejected with HTTP 422.
  - Invalid status or priority values are rejected with HTTP 422.
  - Administrators can update and delete cases via API (HTTP 200).
  - Nonexistent case IDs return HTTP 404.
- **Bonus Role Verification (`tests/Feature/EngineerRoleTest.php`)**:
  - Engineers can create cases via Web and API.
  - Engineers can update cases.
  - Engineers are strictly blocked from deleting cases (HTTP 403).

---

## 8. RESTful API Documentation

All protected endpoints require an `Authorization: Bearer <token>` header obtained from `/api/login`.

### Endpoint Summary

| Method | Endpoint | Access Level | Description |
|---|---|---|---|
| `POST` | `/api/login` | Public | Authenticate user and receive Sanctum bearer token |
| `POST` | `/api/logout` | Authenticated | Revoke current access token |
| `GET` | `/api/user` | Authenticated | Retrieve authenticated user profile |
| `GET` | `/api/cases` | Admin, Viewer, Engineer | List cases (supports pagination, filtering, search) |
| `POST` | `/api/cases` | Admin, Engineer | Create a new medical implant case |
| `GET` | `/api/cases/{id}` | Admin, Viewer, Engineer | Fetch detailed specifications for a single case |
| `PUT` | `/api/cases/{id}` | Admin, Engineer | Update case details and workflow status |
| `DELETE` | `/api/cases/{id}` | Admin Only | Permanently delete a case |

### Standard Response Envelope
All API endpoints return consistent JSON envelopes:
```json
{
  "success": true,
  "data": { ... },
  "message": "Operation completed successfully"
}
```

### Example API Requests

#### 1. Login Request
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@jajalmedical.com",
    "password": "Password123!"
  }'
```
**Response (HTTP 200)**:
```json
{
  "success": true,
  "data": {
    "token": "1|qX1W09...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Medical Administrator",
      "email": "admin@jajalmedical.com",
      "role": "admin"
    }
  },
  "message": "Login successful"
}
```

#### 2. Create Case Request
```bash
curl -X POST http://127.0.0.1:8000/api/cases \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <YOUR_TOKEN>" \
  -d '{
    "case_number": "JM-900201",
    "patient_reference": "DUMMY-CR-9901",
    "surgeon_name": "Dr. Sarah Al-Mansoor, FRCS",
    "implant_type": "Custom PEEK Cranial Implant",
    "status": "Planning",
    "priority": "High",
    "surgery_date": "2026-11-15"
  }'
```
**Response (HTTP 201)**:
```json
{
  "success": true,
  "data": {
    "id": 26,
    "case_number": "JM-900201",
    "patient_reference": "DUMMY-CR-9901",
    "surgeon_name": "Dr. Sarah Al-Mansoor, FRCS",
    "implant_type": "Custom PEEK Cranial Implant",
    "status": "Planning",
    "priority": "High",
    "surgery_date": "2026-11-15",
    "created_by": 1,
    "created_at": "2026-10-14T06:40:00+00:00",
    "updated_at": "2026-10-14T06:40:00+00:00"
  },
  "message": "Case created successfully"
}
```

#### 3. Duplicate Case Number Rejection
Submitting an existing `case_number`:
**Response (HTTP 422)**:
```json
{
  "success": false,
  "message": "Validation failure.",
  "errors": {
    "case_number": [
      "A case with this Case Number already exists."
    ]
  }
}
```

#### 4. Unauthorized Viewer Mutation Rejection
A Viewer attempting to delete a case:
**Response (HTTP 403)**:
```json
{
  "success": false,
  "data": null,
  "message": "Forbidden. You do not have permission to perform this action."
}
```

---

## 9. Architecture & Design Rationale

1. **Why `MedicalCase` instead of `class Case`**:
   In PHP 8.1+, `case` is a reserved keyword for enumerations (`enum Status { case Draft; }`). Defining a class named `Case` generates a fatal parse error. Following Laravel best practices, the model is named `MedicalCase` and maps to the `cases` table.
2. **De-Identified Patient Dummy References**:
   In strict adherence to medical privacy and HIPAA software standards, patient names are never collected or stored. Synthetic identifiers (`DUMMY-XX-XXXX`) are generated by `MedicalCaseFactory`.
3. **Double-Layered Duplicate Prevention**:
   Case number uniqueness is enforced at both the application level (`unique:cases,case_number` in Form Requests) and the database schema level (`$table->string('case_number')->unique()`).
4. **Graceful Public Holiday Integration**:
   External API calls (`date.nager.at`) use a strict 3-second timeout and exception catching. If the external service is unavailable, surgical scheduling continues uninterrupted with a graceful fallback.

---

## 10. Supplementary Documentation

- **Database Architecture & ER Diagram**: [docs/database.md](docs/database.md)
- **Requirements Traceability Matrix (RTM)**: [docs/traceability.md](docs/traceability.md)
- **AWS Production Deployment Strategy**: [docs/deployment.md](docs/deployment.md)
- **Postman API Collection**: [docs/postman_collection.json](docs/postman_collection.json)
