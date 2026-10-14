# Database Architecture & Entity Relationship Documentation

This document describes the database design for the **Medical Case Management System (Jajal Medical)**, detailing table structures, indexes, foreign keys, and Eloquent model relationships.

---

## 1. Entity Relationship (ER) Overview

```text
+-----------------------------------+          +--------------------------------------------+
|               users               |          |                   cases                    |
+-----------------------------------+          +--------------------------------------------+
| id                  BIGINT (PK)   |<---+     | id                  BIGINT (PK)            |
| name                VARCHAR(255)  |    |     | case_number         VARCHAR(50) (UQ, IDX)  |
| email               VARCHAR(255)  |    |     | patient_reference   VARCHAR(100)           |
| email_verified_at   TIMESTAMP     |    +-----| created_by          BIGINT (FK)            |
| password            VARCHAR(255)  |          | surgeon_name        VARCHAR(150)           |
| role                VARCHAR(50)   |          | implant_type        VARCHAR(150)           |
| remember_token      VARCHAR(100)  |          | status              VARCHAR(50) (IDX)      |
| created_at          TIMESTAMP     |          | priority            VARCHAR(50)            |
| updated_at          TIMESTAMP     |          | surgery_date        DATE (NULLABLE)        |
+-----------------------------------+          | created_at          TIMESTAMP              |
                                               | updated_at          TIMESTAMP              |
                                               +--------------------------------------------+
```

### Cardinality & Relationship
- **One User can author many Cases**: `users (1) -------- (many) cases`
- **users.id** &rarr; **cases.created_by**
- Foreign key constraint: `ON DELETE CASCADE`

---

## 2. Table Specifications

### A. `users` Table
Stores authentication credentials, profile identifiers, and RBAC system roles (`admin`, `viewer`, `engineer`).

| Column Name | Data Type | Nullable | Default | Description / Constraints |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary Key |
| `name` | `VARCHAR(255)` | No | None | User's full name |
| `email` | `VARCHAR(255)` | No | None | Unique login email (`UNIQUE` index) |
| `email_verified_at` | `TIMESTAMP` | Yes | `NULL` | Email verification timestamp |
| `password` | `VARCHAR(255)` | No | None | Bcrypt-hashed password |
| `role` | `VARCHAR(50)` | No | `'viewer'` | Role: `'admin'`, `'viewer'`, or `'engineer'` |
| `remember_token` | `VARCHAR(100)` | Yes | `NULL` | "Remember me" session token |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Laravel timestamp |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Laravel timestamp |

#### Indexes on `users`
- `PRIMARY KEY` on `id`
- `UNIQUE INDEX` on `email` (`users_email_unique`)

---

### B. `cases` Table
Stores patient-specific implant manufacturing records, statuses, attending surgeons, and surgical dates.

| Column Name | Data Type | Nullable | Default | Description / Constraints |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary Key |
| `case_number` | `VARCHAR(50)` | No | None | Unique case code (e.g., `JM-100201`), `UNIQUE` index |
| `patient_reference` | `VARCHAR(100)` | No | None | De-identified clinical dummy code (e.g., `DUMMY-CR-1042`) |
| `surgeon_name` | `VARCHAR(150)` | No | None | Name and credentials of attending surgeon |
| `implant_type` | `VARCHAR(150)` | No | None | Specific implant specification (e.g., Cranial Plate, Hip Stem) |
| `status` | `VARCHAR(50)` | No | `'Draft'` | Clinical workflow status, indexed |
| `priority` | `VARCHAR(50)` | No | `'Medium'` | Priority level (`Low`, `Medium`, `High`) |
| `surgery_date` | `DATE` | Yes | `NULL` | Scheduled surgery date |
| `created_by` | `BIGINT UNSIGNED` | No | None | Foreign Key referencing `users(id)` |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Laravel timestamp |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Laravel timestamp |

#### Indexes on `cases`
- `PRIMARY KEY` on `id`
- `UNIQUE INDEX` on `case_number` (`cases_case_number_unique`) &mdash; Prevents duplicate case numbers at DB level
- `INDEX` on `status` (`cases_status_index`) &mdash; Accelerates dashboard filtering and listing queries
- `FOREIGN KEY` on `created_by` referencing `users(id)` (`cases_created_by_foreign`)

---

## 3. Allowed Domain Values

### Case Statuses (`MedicalCase::STATUSES`)
1. **Draft**: Initial case intake and preliminary specification collection.
2. **Planning**: 3D reconstruction, CAD design, and anatomical sizing in progress.
3. **In Review**: Surgical design submitted to surgeon for clinical validation.
4. **Approved**: Surgeon approval granted; released to CNC/3D-printing manufacturing.
5. **Completed**: Medical implant manufactured, sterilized, delivered, and implanted.
6. **Cancelled**: Surgery or manufacturing canceled by surgeon or hospital.

### Case Priorities (`MedicalCase::PRIORITIES`)
1. **Low**: Routine elective reconstruction (scheduled weeks in advance).
2. **Medium**: Standard clinical turnaround.
3. **High**: Urgent trauma or oncology resection requiring accelerated production.

---

## 4. Eloquent ORM Implementation & PHP Technical Note

### Why `MedicalCase` instead of `class Case`
In PHP 8.1 and later, `case` is a reserved language keyword reserved for enumeration declarations (`enum Role { case Admin; }`). Defining `class Case` triggers a fatal `PHP Parse error: syntax error, unexpected token "case"`.

In accordance with Laravel best practices:
- Eloquent Model: `App\Models\MedicalCase`
- Database Table: `cases` (`protected $table = 'cases';`)
- Relationship on User: `$user->cases()` (`hasMany(MedicalCase::class, 'created_by')`)
- Relationship on MedicalCase: `$case->creator()` / `$case->user()` (`belongsTo(User::class, 'created_by')`)
