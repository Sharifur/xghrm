# XGHRM — AI Agent Integration API

This document covers every endpoint the AI agent (cortex-os HR agent) calls, with exact request payloads, response shapes, status codes, and field-level notes.

---

## Base URL

```
https://xghrm.test/api/ai-agent
```

Replace with your production domain. All paths below are relative to this base.

---

## Authentication

Every request must carry the secret key issued from the admin panel.

```
X-Signature: <secret_key>
```

The middleware validates the header against all active API applications.  
Requests missing or with a wrong `X-Signature` are rejected immediately.

```json
HTTP 401
{ "error": "Unauthorized" }
```

**How to get a key:**  
Admin panel → AI Applications → Create Application → copy the secret from the one-time reveal modal.  
The secret is a 64-character hex string. Store it in cortex-os as an environment variable.

**Content-Type for all POST/PATCH requests:**
```
Content-Type: application/json
```

---

## Data Models

### Employee

```json
{
  "id": "12",
  "name": "Rahim Uddin",
  "email": "rahim@company.com",
  "phone": "01700000000",
  "role": "Software Engineer",
  "department": "Software Engineer",
  "salary": 50000.0,
  "currency": "BDT",
  "joinedAt": "2023-01-15",
  "probationUntil": "2023-04-15",
  "contractEndsAt": null,
  "leaveBalance": null,
  "bankAccount": "Dutch-Bangla Bank — 1234567890",
  "active": true,
  "photoUrl": null
}
```

> `role` and `department` both map to the employee's category title.  
> `leaveBalance` is currently `null` — not yet calculated from attendance logs.  
> `probationUntil` maps to the `incrementMonth` field in the database.

---

### Leave Request

```json
{
  "id": "45",
  "employeeId": "12",
  "employeeName": "Rahim Uddin",
  "type": "sick",
  "fromDate": "2025-12-20",
  "toDate": "2025-12-20",
  "totalDays": 1,
  "reason": null,
  "status": "pending",
  "decisionReason": null,
  "decidedAt": null,
  "createdAt": "2025-12-18T10:30:00.000000Z"
}
```

**Status values:** `pending` | `approved` | `rejected`

**Type mapping — what to send vs. what is stored:**

| API type (send this) | Stored internally as |
|---|---|
| `annual` | `leave` |
| `sick` | `sick-leave` |
| `unpaid` | `leave` |
| `maternity` | `leave` |
| `paternity` | `leave` |
| `other` | `leave` |

> `fromDate` and `toDate` are the same value — the system records one attendance log entry per day. Multi-day leaves require one request per day, or submit once and the agent tracks range separately.

---

### WFH Request

```json
{
  "id": "88",
  "employeeId": "12",
  "employeeName": "Rahim Uddin",
  "date": "2025-12-20",
  "reason": null,
  "status": "pending",
  "decisionReason": null,
  "decidedAt": null,
  "createdAt": "2025-12-18T08:00:00.000000Z"
}
```

**Status values:** `pending` | `approved` | `rejected`

---

### Payslip

```json
{
  "id": "33",
  "employeeId": "12",
  "employeeName": "Rahim Uddin",
  "month": "2025-12",
  "baseSalary": 50000.0,
  "bonus": 5000.0,
  "deductions": 2000.0,
  "netSalary": 53000.0,
  "currency": "BDT",
  "workingDays": null,
  "presentDays": null,
  "status": "approved",
  "approvedAt": "2025-12-25T12:00:00.000000Z",
  "paidAt": null,
  "createdAt": "2025-12-25T09:00:00.000000Z"
}
```

> `bonus` and `deductions` are stored inside the `extraEarningFields` and `extraDeductionFields` JSON columns. Use the `PATCH /payslips/{id}` endpoint to set them.  
> `workingDays` and `presentDays` are `null` — not yet derived from attendance logs.  
> `status` is always returned as `"approved"` — the system does not distinguish draft/paid states internally yet.

---

## Endpoints

---

### Employees

---

#### `GET /employees`

Returns employees. Defaults to active only.

**Query params**

| Param | Type | Default | Description |
|---|---|---|---|
| `active` | `true \| false \| all` | `true` | Filter by active status |
| `department` | string | — | Exact match on category title |

**Example request**
```
GET /api/ai-agent/employees?active=true
X-Signature: abc123...
```

**Response `200`**
```json
{
  "data": [
    {
      "id": "12",
      "name": "Rahim Uddin",
      "email": "rahim@company.com",
      "phone": "01700000000",
      "role": "Software Engineer",
      "department": "Software Engineer",
      "salary": 50000.0,
      "currency": "BDT",
      "joinedAt": "2023-01-15",
      "probationUntil": null,
      "contractEndsAt": null,
      "leaveBalance": null,
      "bankAccount": null,
      "active": true,
      "photoUrl": null
    }
  ],
  "total": 1
}
```

---

#### `GET /employees/{id}`

Returns a single employee by ID.

**Example request**
```
GET /api/ai-agent/employees/12
X-Signature: abc123...
```

**Response `200`** — Employee object (same shape as above)

**Response `404`**
```json
{ "error": "Employee not found" }
```

---

#### `GET /employees/today/on-leave`

Returns employees with an approved leave log for today.

**Example request**
```
GET /api/ai-agent/employees/today/on-leave
X-Signature: abc123...
```

**Response `200`**
```json
{
  "data": [
    {
      "employeeId": "12",
      "employeeName": "Rahim Uddin",
      "leaveType": "sick",
      "fromDate": "2025-12-20",
      "toDate": "2025-12-20"
    }
  ]
}
```

---

#### `GET /employees/today/wfh`

Returns employees with an approved WFH log for today.

**Response `200`**
```json
{
  "data": [
    {
      "employeeId": "14",
      "employeeName": "Karim Hassan",
      "date": "2025-12-20"
    }
  ]
}
```

---

#### `GET /employees/alerts`

Returns time-sensitive HR alerts within a look-ahead window.

**Query params**

| Param | Type | Default | Description |
|---|---|---|---|
| `withinDays` | integer | `7` | Look-ahead window in days |

**Example request**
```
GET /api/ai-agent/employees/alerts?withinDays=7
X-Signature: abc123...
```

**Response `200`**
```json
{
  "probationEnding": [
    {
      "employeeId": "15",
      "employeeName": "Nadia Islam",
      "probationUntil": "2025-12-22"
    }
  ],
  "contractExpiring": [],
  "birthdays": [
    {
      "employeeId": "12",
      "employeeName": "Rahim Uddin",
      "date": "2025-12-20"
    }
  ],
  "workAnniversaries": [
    {
      "employeeId": "10",
      "employeeName": "Farhan Ahmed",
      "years": 3,
      "date": "2025-12-21"
    }
  ]
}
```

> `contractExpiring` always returns `[]` — the system does not have a contract end date field.  
> `probationEnding` uses the `incrementMonth` field on the employee record.

---

### Leave Requests

All leave data is backed by the `attendance_logs` table with types `leave`, `sick-leave`, `paid-leave`.

---

#### `GET /leave-requests`

**Query params**

| Param | Type | Description |
|---|---|---|
| `status` | `pending \| approved \| rejected \| all` | Default: `all` |
| `employeeId` | string | Filter to one employee |
| `month` | `YYYY-MM` | Filter by month of the leave date |

**Example request**
```
GET /api/ai-agent/leave-requests?status=pending&month=2025-12
X-Signature: abc123...
```

**Response `200`**
```json
{
  "data": [ /* LeaveRequest[] */ ],
  "total": 5,
  "pendingCount": 2
}
```

---

#### `GET /leave-requests/pending`

Shorthand for `GET /leave-requests?status=pending`. Used for the daily CRON digest.

**Response `200`**
```json
{
  "data": [ /* LeaveRequest[] */ ],
  "total": 3
}
```

---

#### `POST /leave-requests`

Submit a new leave request on behalf of an employee.

**Request body**
```json
{
  "employeeId": "12",
  "type": "sick",
  "fromDate": "2025-12-20",
  "toDate": "2025-12-20",
  "reason": "Fever"
}
```

**Field rules**

| Field | Required | Notes |
|---|---|---|
| `employeeId` | yes | Must exist and be active |
| `type` | yes | `annual \| sick \| unpaid \| maternity \| paternity \| other` |
| `fromDate` | yes | Must not be in the past (`after_or_equal:today`) |
| `toDate` | yes | Must be `after_or_equal:fromDate` |
| `reason` | no | Free text |

**Response `201`** — LeaveRequest object

**Response `422`** — Validation error
```json
{
  "message": "The from date field must be a date after or equal to today.",
  "errors": {
    "fromDate": ["The from date field must be a date after or equal to today."]
  }
}
```

---

#### `POST /leave-requests/{id}/approve`

Approve a pending leave request.

**Request body**
```json
{
  "reason": "Approved by manager"
}
```

> `reason` is optional — send `{}` or omit body if no reason.

**Response `200`** — Updated LeaveRequest object (status becomes `approved`)

**Response `409`**
```json
{ "error": "Leave request is not in pending status" }
```

**Response `404`**
```json
{ "error": "Leave request not found" }
```

---

#### `POST /leave-requests/{id}/reject`

Reject a pending leave request.

**Request body**
```json
{
  "reason": "Team is at full capacity that week"
}
```

> `reason` is required for rejection.

**Response `200`** — Updated LeaveRequest object (status becomes `rejected`)

**Response `409`**
```json
{ "error": "Leave request is not in pending status" }
```

---

### WFH Requests

Backed by `attendance_logs` with type `work-from-home`.

---

#### `GET /wfh-requests/pending`

Returns all pending WFH requests. Used for the daily CRON digest.

**Response `200`**
```json
{
  "data": [ /* WfhRequest[] */ ],
  "total": 2
}
```

---

#### `POST /wfh-requests`

Submit a new WFH request.

**Request body**
```json
{
  "employeeId": "12",
  "date": "2025-12-20",
  "reason": "Doctor appointment in the morning"
}
```

**Field rules**

| Field | Required | Notes |
|---|---|---|
| `employeeId` | yes | Must exist |
| `date` | yes | Must not be in the past |
| `reason` | no | Free text |

**Response `201`** — WfhRequest object

**Response `409`** — Duplicate
```json
{ "error": "WFH request already exists for this date" }
```

---

#### `POST /wfh-requests/{id}/approve`

**Request body**
```json
{
  "reason": "Approved"
}
```

> `reason` is optional.

**Response `200`** — Updated WfhRequest object (status becomes `approved`)

**Response `409`**
```json
{ "error": "WFH request is not in pending status" }
```

---

#### `POST /wfh-requests/{id}/reject`

**Request body**
```json
{
  "reason": "All hands meeting in office"
}
```

> `reason` is required.

**Response `200`** — Updated WfhRequest object (status becomes `rejected`)

---

### Payslips

Backed by the `salary_slips` table.

---

#### `GET /payslips`

**Query params**

| Param | Type | Required | Description |
|---|---|---|---|
| `month` | `YYYY-MM` | yes | Filter by month |

**Example request**
```
GET /api/ai-agent/payslips?month=2025-12
X-Signature: abc123...
```

**Response `200`**
```json
{
  "data": [ /* Payslip[] */ ],
  "total": 12,
  "summary": {
    "month": "2025-12",
    "totalNet": 630000.0,
    "currency": "BDT",
    "approvedCount": 12,
    "draftCount": 0
  }
}
```

---

#### `GET /payslips/{employeeId}/{month}`

Get the payslip for a specific employee and month.

**Example request**
```
GET /api/ai-agent/payslips/12/2025-12
X-Signature: abc123...
```

**Response `200`** — Payslip object

**Response `404`**
```json
{ "error": "Payslip not found" }
```

---

#### `POST /payslips/generate`

Generate payslips for all active employees for a month. Idempotent — skips employees who already have a slip for that month. Employees with zero attendance (C/In = 0) for the month are also skipped.

Auto-calculates on generation:
- **Lunch allowance** — 50 BDT × present days (C/In count), stored as `{"description": "N Days Lunch", "amount": X}` in earnings
- **Advance salary deduction** — total advance taken for that month from `advance_salaries`, stored as `{"description": "Advance Salary", "amount": X}` in deductions

**Request body**
```json
{
  "month": "2025-12"
}
```

**Response `201`**
```json
{
  "generated": 10,
  "skipped": 2,
  "noAttendance": 1,
  "data": [ /* Payslip[] — generated slips only (not skipped) */ ]
}
```

---

#### `PATCH /payslips/{id}`

Add or update a manual bonus or deduction entry on a payslip. Both fields are optional. Auto-generated entries (lunch, advance) are preserved — this only sets the "Bonus" and "Manual Deduction" line items.

**Request body**
```json
{
  "bonus": 5000,
  "deductions": 2000
}
```

**Response `200`** — Updated Payslip object with recalculated `netSalary`

**Response `404`**
```json
{ "error": "Payslip not found" }
```

---

#### `POST /payslips/{id}/approve`

Mark a payslip as approved.

**Request body** — empty `{}`

**Response `200`** — Payslip object

---

#### `POST /payslips/{id}/mark-paid`

Mark a payslip as paid after salary is disbursed.

**Request body**
```json
{
  "paidAt": "2025-12-28T10:00:00Z"
}
```

> `paidAt` is optional — omit to use the current timestamp.

**Response `200`** — Payslip object

---

#### `GET /payslips/export/{month}`

Download all payslips for a month as a CSV file.

**Example request**
```
GET /api/ai-agent/payslips/export/2025-12
X-Signature: abc123...
```

**Response `200`**
- `Content-Type: text/csv`
- `Content-Disposition: attachment; filename="payslips-2025-12.csv"`

**CSV columns:**
```
employee_id, name, department, role, base_salary, bonus, deductions, net_salary, currency, status
```

---

## Error Reference

| HTTP Code | When |
|---|---|
| `200` | Successful read or update |
| `201` | Successful create |
| `401` | Missing or invalid `X-Signature` |
| `404` | Resource not found |
| `409` | Conflict — duplicate request or wrong status for action |
| `422` | Validation failure — check `errors` object for field details |
| `500` | Internal server error |

**All error responses use this shape:**
```json
{ "error": "Human-readable message" }
```

**Validation errors (422) use Laravel's default shape:**
```json
{
  "message": "The field is required.",
  "errors": {
    "fieldName": ["The field is required."]
  }
}
```

---

## Daily Agent Schedule — What to Call and When

### Every day at 9:00 AM

| Step | Endpoint | Purpose |
|---|---|---|
| 1 | `GET /leave-requests/pending` | Collect pending leave approvals |
| 2 | `GET /wfh-requests/pending` | Collect pending WFH approvals |
| 3 | `GET /employees/today/on-leave` | Who is absent today |
| 4 | `GET /employees/today/wfh` | Who is WFH today |
| 5 | `GET /employees/alerts?withinDays=7` | Probation endings, birthdays, anniversaries |

For each pending leave → send individual Telegram message with Approve / Reject.  
For each pending WFH → send individual Telegram message with Approve / Reject.  
Compile one summary digest from steps 3–5.

### On Telegram "Approve" for leave

```
POST /leave-requests/{id}/approve
Body: {}
```

### On Telegram "Reject" for leave (after asking for reason)

```
POST /leave-requests/{id}/reject
Body: { "reason": "<reason from Telegram>" }
```

### On Telegram "Approve" / "Reject" for WFH

```
POST /wfh-requests/{id}/approve   Body: {}
POST /wfh-requests/{id}/reject    Body: { "reason": "..." }
```

### Monthly on the 25th

| Step | Endpoint | Purpose |
|---|---|---|
| 1 | `POST /payslips/generate` | Generate drafts for current month |
| 2 | `GET /payslips?month=YYYY-MM` | Fetch all generated slips |
| 3 | For each slip → send Telegram with base + net | Await approval |
| 4 | On approval → `POST /payslips/{id}/approve` | Mark approved |
| 5 | On edit → `PATCH /payslips/{id}` then re-show | Update bonus/deductions |
| 6 | After all done → `GET /payslips/export/{month}` | Send CSV download link to Telegram |

---

## Quick Reference — All Endpoints

```
GET    /employees
GET    /employees/{id}
GET    /employees/today/on-leave
GET    /employees/today/wfh
GET    /employees/alerts?withinDays=7

GET    /leave-requests
GET    /leave-requests/pending
POST   /leave-requests
POST   /leave-requests/{id}/approve
POST   /leave-requests/{id}/reject

GET    /wfh-requests/pending
POST   /wfh-requests
POST   /wfh-requests/{id}/approve
POST   /wfh-requests/{id}/reject

GET    /payslips?month=YYYY-MM
GET    /payslips/{employeeId}/{month}
POST   /payslips/generate
PATCH  /payslips/{id}
POST   /payslips/{id}/approve
POST   /payslips/{id}/mark-paid
GET    /payslips/export/{month}
```

---

## Known Limitations

| Field | Status | Notes |
|---|---|---|
| `leaveBalance` | Always `null` | Not calculated yet; can be derived from attendance_logs |
| `workingDays` | Calculated | Working days in the month excluding Friday + Saturday |
| `presentDays` | Calculated | C/In count for the month from attendance_logs |
| `contractEndsAt` | Always `null` | No contract end date field in the database |
| `paidAt` on mark-paid | Persisted | Stored in `salary_slips.paid_at` column (migration added) |
| Multi-day leave in one request | Not supported | System records one log per day; submit per-day or handle range in agent |
| Leave `reason` field | Always `null` in response | Not stored in the current attendance log schema |
| Lunch for WFH days | Included | Lunch is currently counted for all C/In days including work-from-home |

---

## Recommendations (not yet implemented)

| Item | Description |
|---|---|
| Configurable payroll settings | Add an admin settings page for lunch rate (currently hardcoded at 50 BDT), late penalty, and other per-day rates so admins can adjust without a code change |
| Sick/unpaid leave deduction | Deduct proportional daily rate for `leave` and `sick-leave` days. Currently admin enters this manually via PATCH. Formula: `round(salary / workingDays * leaveDays, 2)` |
| Late arrival deduction | Employees checked in after 10:15 AM — optionally deduct a fixed penalty per late day |
| Configurable payroll settings | Move lunch rate (50 BDT) and other per-day rates to an admin settings page so they can be changed without a deploy |
