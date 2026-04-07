# Demo by User Role

Step-by-step demo walkthrough for each role. Use this to prepare testers or to guide a panel through role-specific functionality.

---

## Student

**Login**: `student@spup.edu.ph`

1. Log in → lands on **Student Dashboard**.
2. View current application status (if any).
3. Click **Apply for Good Moral Certificate**.
4. Fill in: reason for request, supporting notes, upload document (optional).
5. Submit → status shows **Pending**.
6. Receive email notification (if SMTP configured).
7. Log back in after admin approval → status shows **Approved**.
8. Download the issued **Good Moral Certificate** (PDF).

**Key points to highlight**: simple UX, no paper forms, instant status tracking.

---

## Admin

**Login**: `admin@spup.edu.ph`

1. Log in → lands on **Admin Dashboard** with pending application count.
2. Navigate to **Applications → Pending**.
3. Click on a student application → review details (name, course, reason).
4. Check: student has no active violations (system may block if violations exist).
5. Click **Approve**.
6. Navigate to **Certificates → Generate**.
7. Select the approved application → click **Generate Certificate**.
8. Preview and download the PDF certificate.
9. Navigate to **Users** → show admin can manage role accounts.

**Key points**: centralized approval, certificate generation, user management.

---

## Dean

**Login**: `dean@spup.edu.ph`

1. Log in → **Dean Dashboard** shows college-level summaries.
2. View applications from students under the Dean's college.
3. Navigate to **Violations** → see major and minor violations per student.
4. Report view: see aggregate data for the college.

**Key points**: oversight of entire college, not just individuals.

---

## Program Coordinator

**Login**: `progcoor@spup.edu.ph`

1. Log in → **Program Coordinator Dashboard**.
2. Navigate to **Courses** → view assigned courses.
3. View students enrolled in courses.
4. View application statuses for students in the program.

**Key points**: course-level view, student monitoring within program.

---

## SecOSA / Moderator

**Login**: `secosa@spup.edu.ph`

1. Log in → **SecOSA Dashboard**.
2. Navigate to **Violations → Major Violations**.
3. Add a major violation to a student record.
4. Navigate to **Violations → Minor Violations**.
5. Add a minor violation.
6. Show that the affected student's application is now blocked.
7. Navigate to **Certificate Management** → can view generated certificates.

**Key points**: violation tracking gates certificate issuance.

---

## PSG Officer

**Login**: `psg@spup.edu.ph`

1. Log in → **PSG Dashboard** (limited view).
2. View student clearance statuses.
3. Cannot access admin, dean, or SecOSA functions.

**Key points**: limited read-only role for student government liaison.

---

## Super Admin

**Login**: super admin account (set up during installation)

1. Full access to all dashboards.
2. Can manage all user accounts and roles.
3. Access to system-level configuration.

---

## Role Access Summary

| Feature | Student | Admin | Dean | ProgCoor | SecOSA | PSG |
|---|---|---|---|---|---|---|
| Apply for certificate | ✅ | — | — | — | — | — |
| Approve applications | — | ✅ | — | — | — | — |
| Generate certificates | — | ✅ | — | — | ✅ (view) | — |
| Manage violations | — | ✅ | view | view | ✅ | — |
| Manage users | — | ✅ | — | — | — | — |
| View college reports | — | ✅ | ✅ | — | — | — |
| View program data | — | — | — | ✅ | — | — |
