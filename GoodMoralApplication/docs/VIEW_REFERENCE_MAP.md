# Comprehensive View Reference Map

> **Generated**: Read-only audit of ALL view references in the project  
> **Total Blade Files**: 133 (including vendor/pagination)  
> **Total Page Views (non-component, non-vendor)**: 96  
> **Total Component Views**: 23  

---

## 1. Controller → View Mappings

### GoodMoral Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `ApplicationController` | `dashboard()` | `dashboard` | `dashboard.blade.php` |
| `ApplicationController` | `notification()` | `notification` | `notification.blade.php` |
| `ApplicationController` | `notificationViolation()` | `notificationViolation` | `notificationViolation.blade.php` |
| `PsgOfficerController` | `dashboard()` | *(redirect to PsgOfficer.applications)* | — |
| `PsgOfficerController` | `showGoodMoralForm()` | `PsgOfficer.good-moral-form` | `PsgOfficer/good-moral-form.blade.php` |
| `PsgOfficerController` | `showPersonalViolations()` | `PsgOfficer.personal-violations` | `PsgOfficer/personal-violations.blade.php` |
| `PsgOfficerController` | `showApplications()` | `PsgOfficer.applications` | `PsgOfficer/applications.blade.php` |
| `RegistrarController` | `goodMoralApplication()` | `registrar.goodMoralApplication` | `registrar/goodMoralApplication.blade.php` |
| `ProgramCoordinatorController` | `major()` | `prog_coor.major` | `prog_coor/major.blade.php` |
| `ProgramCoordinatorController` | `CoorMajorSearch()` | `prog_coor.major` | `prog_coor/major.blade.php` |
| `ProgramCoordinatorController` | `minor()` | `prog_coor.minor` | `prog_coor/minor.blade.php` |
| `StudentOfficerApplicationController` | `showForm()` | `student.apply-officer` | `student/apply-officer.blade.php` |

### Admin Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `Admin\DashboardController` | `dashboard()` | `admin.dashboard` | `admin/dashboard.blade.php` |
| `Admin\ApplicationController` | `applicationDashboard()` | `admin.application` | `admin/Application.blade.php` |
| `Admin\ApplicationController` | `readyForPrintApplications()` | `admin.ready-for-print-applications` | `admin/ready-for-print-applications.blade.php` |
| `Admin\ApplicationController` | `search()` | `admin.application` | `admin/Application.blade.php` |
| `Admin\ApplicationController` | `GMAApporvedByRegistrar()` | `admin.gma-approved-by-registrar` | `admin/gma-approved-by-registrar.blade.php` |
| `Admin\ViolationController` | `AddViolationDashboard()` | `admin.add-violation` | `admin/add-violation.blade.php` |
| `Admin\ViolationController` | `violation()` | `admin.violation` | `admin/violation.blade.php` |
| `Admin\ViolationController` | `violationSearch()` | `admin.violation-grouped` | `admin/violation-grouped.blade.php` |
| `Admin\ViolationController` | `violationDetails()` | `admin.violation-grouped` | `admin/violation-grouped.blade.php` |
| `Admin\ViolatorController` | `AddViolatorDashboard()` | `admin.add-violator` | `admin/add-violator.blade.php` |
| `Admin\ViolatorController` | `addMultipleViolatorsForm()` | `admin.add-multiple-violators` | `admin/add-multiple-violators.blade.php` |
| `Admin\AccountController` | `AddAccountnt()` | `admin.add-account` | `admin/add-account.blade.php` |
| `Admin\ReportController` | `reportsPage()` | `admin.reports` | `admin/reports.blade.php` |
| `Admin\ReportController` | `reportsHistory()` | `admin.reports-history` | `admin/reports-history.blade.php` |
| `Admin\EscalationController` | `escalationNotifications()` | `admin.escalation-notifications` | `admin/escalation-notifications.blade.php` |
| `Admin\StudentOfficerController` | `index()` | `admin.student-officer-applications` | `admin/student-officer-applications.blade.php` |
| `Admin\PsgController` | `psgApplication()` | `admin.psg-application` | `admin/psg-application.blade.php` |
| `Admin\DatabaseSummaryController` | `databaseSummary()` | `admin.database-summary` | `admin/database-summary.blade.php` |

### Dean Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `Dean\DashboardController` | `dashboard()` | `dean.dashboard` | `dean/dashboard.blade.php` |
| `Dean\DashboardController` | `major()` | `dean.major` | `dean/major.blade.php` |
| `Dean\DashboardController` | `minor()` | `dean.minor` | `dean/minor.blade.php` |
| `Dean\ApplicationController` | `application()` | `dean.application` | `dean/application.blade.php` |

### SecOSA Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `SecOSA\DashboardController` | `dashboard()` | `sec_osa.dashboard` | `sec_osa/dashboard.blade.php` |
| `SecOSA\ViolationController` | `major()` | `sec_osa.major` | `sec_osa/major.blade.php` |
| `SecOSA\ViolationController` | `minor()` | `sec_osa.minor` | `sec_osa/minor.blade.php` |
| `SecOSA\ViolationController` | `searchMinor()` | `sec_osa.minor` | `sec_osa/minor.blade.php` |
| `SecOSA\ViolationController` | `searchMajor()` | `sec_osa.major` | `sec_osa/major.blade.php` |
| `SecOSA\ViolationController` | `violation()` | `sec_osa.violations` | `sec_osa/violations.blade.php` |
| `SecOSA\ViolationController` | `viewDepartmentViolations()` | `sec_osa.departmentViolations` | `sec_osa/departmentViolations.blade.php` |
| `SecOSA\ViolationController` | `escalationNotifications()` | `sec_osa.escalationNotifications` | `sec_osa/escalationNotifications.blade.php` |
| `SecOSA\ViolationController` | `showUploadProceedings()` | `sec_osa.upload_proceedings` | `sec_osa/upload_proceedings.blade.php` |
| `SecOSA\ViolationController` | `showMinorDetail()` | *(returns JSON, not a view)* | — |
| `SecOSA\CertificateController` | `application()` | `sec_osa.application` | `sec_osa/application.blade.php` |
| `SecOSA\ViolatorController` | `addViolatorForm()` | `sec_osa.add-violator` | `sec_osa/add-violator.blade.php` |

### Auth Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `AuthenticatedSessionController` | `create()` | `auth.login` | `auth/login.blade.php` |
| `RegisteredUserController` | `create()` | `auth.register` | `auth/register.blade.php` |
| `RegisteredAccountController` | `create()` | `admin.add-account` | `admin/add-account.blade.php` |
| `RegisterViolationController` | `dashboard()` | `PsgOfficer.dashboard` | `PsgOfficer/dashboard.blade.php` |
| `RegisterViolationController` | `create()` | `PsgOfficer.psg-add-violation` | `PsgOfficer/psg-add-violation.blade.php` |
| `RegisterViolationController` | `violator()` | `PsgOfficer.violator` | `PsgOfficer/Violator.blade.php` |
| `RegisterViolationController` | `ViolatorDashboard()` | `PsgOfficer.psg-add-violation` | `PsgOfficer/psg-add-violation.blade.php` |
| `NewPasswordController` | `create()` | `auth.reset-password` | `auth/reset-password.blade.php` |
| `PasswordResetLinkController` | `create()` | `auth.forgot-password` | `auth/forgot-password.blade.php` |
| `ConfirmablePasswordController` | `show()` | `auth.confirm-password` | `auth/confirm-password.blade.php` |
| `EmailVerificationPromptController` | `__invoke()` | `auth.verify-email` | `auth/verify-email.blade.php` |

### Shared Controllers

| Controller | Method | View Name | Blade File |
|---|---|---|---|
| `ProfileController` | `edit()` | *(dynamic by role)* | See Profile Views section below |
| `OrganizationController` | `index()` | `organizations.index` | `organizations/index.blade.php` |
| `OrganizationController` | `create()` | `organizations.create` | `organizations/create.blade.php` |
| `OrganizationController` | `show()` | `organizations.show` | `organizations/show.blade.php` |
| `OrganizationController` | `edit()` | `organizations.edit` | `organizations/edit.blade.php` |
| `PositionController` | `index()` | `positions.index` | `positions/index.blade.php` |
| `PositionController` | `create()` | `positions.create` | `positions/create.blade.php` |
| `PositionController` | `show()` | `positions.show` | `positions/show.blade.php` |
| `PositionController` | `edit()` | `positions.edit` | `positions/edit.blade.php` |
| `CourseController` | `index()` | `admin.courses.index` | `admin/courses/index.blade.php` |
| `DepartmentController` | `index()` | `admin.departments.index` | `admin/departments/index.blade.php` |
| `AcademicYearController` | `index()` | `admin.academic-year.index` | `admin/academic-year/index.blade.php` |

### ProfileController Dynamic View Mapping

The `ProfileController::edit()` method selects a view based on the user's role:

| Role | View Name | Blade File |
|---|---|---|
| `admin` | `profile.admin` | `profile/admin.blade.php` |
| `moderator` | `profile.moderator` | `profile/moderator.blade.php` |
| `dean` | `profile.dean` | `profile/dean.blade.php` |
| `prog_coor` | `profile.prog_coor` | `profile/prog_coor.blade.php` |
| `registrar` | `profile.registrar` | `profile/registrar.blade.php` |
| `student` | `student.profile` | `student/profile.blade.php` |
| `psg_officer` | `profile.psg_officer` | `profile/psg_officer.blade.php` |
| `sec_osa` | `profile.sec_osa` | `profile/sec_osa.blade.php` |
| *(fallback)* | `profile.edit` | `profile/edit.blade.php` |

---

## 2. PDF / Service View References

### ReportController PDF Views

| Report Type | wkhtmltopdf View | DomPDF Fallback View |
|---|---|---|
| Good Moral Applicants | `pdf.wkhtmltopdf.good_moral_applicants_report` | `pdf.good_moral_applicants_report` |
| Residency Applicants | `pdf.wkhtmltopdf.residency_applicants_report` | `pdf.residency_applicants_report` |
| Minor Violators | — | `pdf.minor_violators_report` |
| Major Violators | — (uses PHPWord) | `pdf.major_violators_report` *(DomPDF fallback)* |
| Overall Report | — | `pdf.violators_report` |
| Minor Offenses Overall | — | `pdf.minor_offenses_overall_report` |
| Header (wkhtmltopdf) | `pdf.wkhtmltopdf.header` | — |
| Footer (wkhtmltopdf) | `pdf.wkhtmltopdf.footer` | — |

### CertificateService PDF Views

| Condition | View Name | Blade File |
|---|---|---|
| `certificate_type === 'good_moral'` | `pdf.student_certificate` | `pdf/student_certificate.blade.php` |
| `certificate_type === 'residency'` (student) | `pdf.student_residency_certificate` | `pdf/student_residency_certificate.blade.php` |
| `certificate_type === 'residency'` (other) | `pdf.other_certificate` | `pdf/other_certificate.blade.php` |
| Legacy + violations (student) | `pdf.student_residency_certificate` | `pdf/student_residency_certificate.blade.php` |
| Legacy + violations (other) | `pdf.other_certificate` | `pdf/other_certificate.blade.php` |
| Legacy + no violations | `pdf.student_certificate` | `pdf/student_certificate.blade.php` |

### ReceiptService

| View Name | Blade File |
|---|---|
| `pdf.receipt` | `pdf/receipt.blade.php` |

### Email/Notification Views

| Source | View Name | Blade File |
|---|---|---|
| `CustomResetPasswordNotification` | `emails.password-reset` | `emails/password-reset.blade.php` |
| `CustomResetPasswordNotification` | `emails.password-reset-text` | `emails/password-reset-text.blade.php` |

---

## 3. Route → View Mapping

| Route | View | Source |
|---|---|---|
| `GET /` (name: `welcome`) | `welcome` | `web.php` (direct `Route::get('/', fn() => view('welcome'))`) |

**No `Route::view()` calls found.** All other routes delegate to controllers.

---

## 4. Blade @include / @extends / @component References

| Directive | Partial View | Used In |
|---|---|---|
| `@include('shared.alerts.flash')` | `shared/alerts/flash.blade.php` | ~20+ views (admin, dean, sec_osa, PsgOfficer, registrar, student, etc.) |
| `@include('shared.modals.confirmation-modal')` | `shared/modals/confirmation-modal.blade.php` | `admin/student-officer-applications`, `admin/psg-application` |
| `@include('shared.modals.application-details')` | `shared/modals/application-details.blade.php` | `admin/Application` |
| `@include('certificates._application-form')` | `certificates/_application-form.blade.php` | `dashboard`, `PsgOfficer/good-moral-form` |
| `@include('profile.partials.update-profile-information-form')` | `profile/partials/update-profile-information-form.blade.php` | `profile/edit` |
| `@include('profile.partials.update-password-form')` | `profile/partials/update-password-form.blade.php` | `profile/edit`, `profile/admin` |
| `@include('profile.partials.delete-user-form')` | `profile/partials/delete-user-form.blade.php` | `profile/edit` |

---

## 5. Blade `<x-component>` Usage

### Layout Components

| Component Tag | Resolved To | Used In |
|---|---|---|
| `<x-dashboard-layout>` | `components/dashboard-layout.blade.php` | ALL dashboard page views (admin, dean, sec_osa, PsgOfficer, student, profile, etc.) |
| `<x-guest-layout>` | `layouts/guest.blade.php` | All auth views (login, register, forgot-password, reset-password, verify-email, confirm-password) |

### Navigation Components

| Component Tag | Blade File | Used In |
|---|---|---|
| `<x-admin-navigation />` | `components/admin-navigation.blade.php` | All admin/* views, organizations/*, positions/*, admin/courses/*, admin/departments/*, profile/admin |
| `<x-dean-navigation />` | `components/dean-navigation.blade.php` | dean/*, profile/dean |
| `<x-moderator-navigation />` | `components/moderator-navigation.blade.php` | All sec_osa/* views |
| `<x-psg-officer-navigation />` | `components/psg-officer-navigation.blade.php` | All PsgOfficer/* views |
| `<x-prog-coor-navigation />` | `components/prog-coor-navigation.blade.php` | prog_coor/* views |
| `<x-registrar-navigation />` | `components/registrar-navigation.blade.php` | registrar/goodMoralApplication |
| `<x-student-navigation />` | `components/student-navigation.blade.php` | dashboard, notification, notificationViolation, student/profile, student/apply-officer |
| `<x-alumni-navigation />` | `components/alumni-navigation.blade.php` | dashboard, notification, notificationViolation, student/profile |

### UI Components

| Component Tag | Blade File | Used In |
|---|---|---|
| `<x-input-error>` | `components/input-error.blade.php` | auth/register, auth/login, auth/forgot-password, auth/reset-password, auth/confirm-password, admin/add-account, admin/add-violation, admin/add-violator, profile/admin |
| `<x-input-label>` | `components/input-label.blade.php` | profile/admin |
| `<x-text-input>` | `components/text-input.blade.php` | profile/admin |
| `<x-primary-button>` | `components/primary-button.blade.php` | auth/verify-email, profile/admin |
| `<x-auth-session-status>` | `components/auth-session-status.blade.php` | auth/login, auth/forgot-password |
| `<x-shared.modals.confirm-action>` | `components/shared/modals/confirm-action.blade.php` | admin/Application, admin/add-violation, admin/add-account |
| `<x-icon>` | `components/icon.blade.php` | student/profile |

### View Component Classes (app/View/Components/)

| Class | Renders View | Blade File |
|---|---|---|
| `DashboardLayout` | `components.dashboard-layout` | `components/dashboard-layout.blade.php` |
| `GuestLayout` | `layouts.guest` | `layouts/guest.blade.php` |
| `IconLogout` | `components.icon-logout` | `components/icon-logout.blade.php` |
| `IconUserGroup` | `components.icon-user-group` | **⚠️ MISSING — see Issues** |

---

## 6. Complete Blade File Inventory

### Page Views (referenced from controllers)

| # | Blade File | Referenced By |
|---|---|---|
| 1 | `welcome.blade.php` | Route `GET /` |
| 2 | `dashboard.blade.php` | `ApplicationController::dashboard()` |
| 3 | `notification.blade.php` | `ApplicationController::notification()` |
| 4 | `notificationViolation.blade.php` | `ApplicationController::notificationViolation()` |
| 5 | `auth/login.blade.php` | `AuthenticatedSessionController::create()` |
| 6 | `auth/register.blade.php` | `RegisteredUserController::create()` |
| 7 | `auth/forgot-password.blade.php` | `PasswordResetLinkController::create()` |
| 8 | `auth/reset-password.blade.php` | `NewPasswordController::create()` |
| 9 | `auth/verify-email.blade.php` | `EmailVerificationPromptController` |
| 10 | `auth/confirm-password.blade.php` | `ConfirmablePasswordController::show()` |
| 11 | `admin/dashboard.blade.php` | `Admin\DashboardController::dashboard()` |
| 12 | `admin/Application.blade.php` | `Admin\ApplicationController::applicationDashboard()`, `search()` |
| 13 | `admin/violation.blade.php` | `Admin\ViolationController::violation()` |
| 14 | `admin/violation-grouped.blade.php` | `Admin\ViolationController::violationSearch()`, `violationDetails()` |
| 15 | `admin/add-violation.blade.php` | `Admin\ViolationController::AddViolationDashboard()` |
| 16 | `admin/add-violator.blade.php` | `Admin\ViolatorController::AddViolatorDashboard()` |
| 17 | `admin/add-multiple-violators.blade.php` | `Admin\ViolatorController::addMultipleViolatorsForm()` |
| 18 | `admin/add-account.blade.php` | `Admin\AccountController::AddAccountnt()`, `RegisteredAccountController::create()` |
| 19 | `admin/ready-for-print-applications.blade.php` | `Admin\ApplicationController::readyForPrintApplications()` |
| 20 | `admin/gma-approved-by-registrar.blade.php` | `Admin\ApplicationController::GMAApporvedByRegistrar()` |
| 21 | `admin/reports.blade.php` | `Admin\ReportController::reportsPage()` |
| 22 | `admin/reports-history.blade.php` | `Admin\ReportController::reportsHistory()` |
| 23 | `admin/student-officer-applications.blade.php` | `Admin\StudentOfficerController::index()` |
| 24 | `admin/psg-application.blade.php` | `Admin\PsgController::psgApplication()` |
| 25 | `admin/escalation-notifications.blade.php` | `Admin\EscalationController::escalationNotifications()` |
| 26 | `admin/database-summary.blade.php` | `Admin\DatabaseSummaryController::databaseSummary()` |
| 27 | `admin/courses/index.blade.php` | `CourseController::index()` |
| 28 | `admin/departments/index.blade.php` | `DepartmentController::index()` |
| 29 | `admin/academic-year/index.blade.php` | `AcademicYearController::index()` |
| 30 | `dean/dashboard.blade.php` | `Dean\DashboardController::dashboard()` |
| 31 | `dean/application.blade.php` | `Dean\ApplicationController::application()` |
| 32 | `dean/major.blade.php` | `Dean\DashboardController::major()` |
| 33 | `dean/minor.blade.php` | `Dean\DashboardController::minor()` |
| 34 | `sec_osa/dashboard.blade.php` | `SecOSA\DashboardController::dashboard()` |
| 35 | `sec_osa/application.blade.php` | `SecOSA\CertificateController::application()` |
| 36 | `sec_osa/violations.blade.php` | `SecOSA\ViolationController::violation()` |
| 37 | `sec_osa/major.blade.php` | `SecOSA\ViolationController::major()`, `searchMajor()` |
| 38 | `sec_osa/minor.blade.php` | `SecOSA\ViolationController::minor()`, `searchMinor()` |
| 39 | `sec_osa/departmentViolations.blade.php` | `SecOSA\ViolationController::viewDepartmentViolations()` |
| 40 | `sec_osa/escalationNotifications.blade.php` | `SecOSA\ViolationController::escalationNotifications()` |
| 41 | `sec_osa/upload_proceedings.blade.php` | `SecOSA\ViolationController::showUploadProceedings()` |
| 42 | `sec_osa/add-violator.blade.php` | `SecOSA\ViolatorController::addViolatorForm()` |
| 43 | `PsgOfficer/dashboard.blade.php` | `RegisterViolationController::dashboard()` |
| 44 | `PsgOfficer/applications.blade.php` | `PsgOfficerController::showApplications()` |
| 45 | `PsgOfficer/good-moral-form.blade.php` | `PsgOfficerController::showGoodMoralForm()` |
| 46 | `PsgOfficer/psg-add-violation.blade.php` | `RegisterViolationController::create()`, `ViolatorDashboard()` |
| 47 | `PsgOfficer/Violator.blade.php` | `RegisterViolationController::violator()` |
| 48 | `PsgOfficer/personal-violations.blade.php` | `PsgOfficerController::showPersonalViolations()` |
| 49 | `registrar/goodMoralApplication.blade.php` | `RegistrarController::goodMoralApplication()` |
| 50 | `student/profile.blade.php` | `ProfileController::edit()` (student role) |
| 51 | `student/apply-officer.blade.php` | `StudentOfficerApplicationController::showForm()` |
| 52 | `profile/admin.blade.php` | `ProfileController::edit()` (admin role) |
| 53 | `profile/dean.blade.php` | `ProfileController::edit()` (dean role) |
| 54 | `profile/moderator.blade.php` | `ProfileController::edit()` (moderator role) |
| 55 | `profile/prog_coor.blade.php` | `ProfileController::edit()` (prog_coor role) |
| 56 | `profile/psg_officer.blade.php` | `ProfileController::edit()` (psg_officer role) |
| 57 | `profile/registrar.blade.php` | `ProfileController::edit()` (registrar role) |
| 58 | `profile/sec_osa.blade.php` | `ProfileController::edit()` (sec_osa role) |
| 59 | `profile/edit.blade.php` | `ProfileController::edit()` (fallback) |
| 60 | `prog_coor/major.blade.php` | `ProgramCoordinatorController::major()`, `CoorMajorSearch()` |
| 61 | `prog_coor/minor.blade.php` | `ProgramCoordinatorController::minor()` |
| 62 | `organizations/index.blade.php` | `OrganizationController::index()` |
| 63 | `organizations/create.blade.php` | `OrganizationController::create()` |
| 64 | `organizations/show.blade.php` | `OrganizationController::show()` |
| 65 | `organizations/edit.blade.php` | `OrganizationController::edit()` |
| 66 | `positions/index.blade.php` | `PositionController::index()` |
| 67 | `positions/create.blade.php` | `PositionController::create()` |
| 68 | `positions/show.blade.php` | `PositionController::show()` |
| 69 | `positions/edit.blade.php` | `PositionController::edit()` |

### Partial / Include Views

| # | Blade File | Included By |
|---|---|---|
| 70 | `certificates/_application-form.blade.php` | `dashboard`, `PsgOfficer/good-moral-form` |
| 71 | `shared/alerts/flash.blade.php` | ~20+ views |
| 72 | `shared/modals/confirmation-modal.blade.php` | admin views |
| 73 | `shared/modals/confirm-action.blade.php` | *(not used as @include; component version used instead)* |
| 74 | `shared/modals/application-details.blade.php` | `admin/Application` |
| 75 | `shared/modals/reject-with-reason.blade.php` | admin/sec_osa views |
| 76 | `profile/partials/update-profile-information-form.blade.php` | `profile/edit` |
| 77 | `profile/partials/update-password-form.blade.php` | `profile/edit`, `profile/admin` |
| 78 | `profile/partials/delete-user-form.blade.php` | `profile/edit` |

### PDF Views

| # | Blade File | Referenced By |
|---|---|---|
| 79 | `pdf/violators_report.blade.php` | `ReportController` (Overall Report) |
| 80 | `pdf/student_certificate.blade.php` | `CertificateService` |
| 81 | `pdf/student_residency_certificate.blade.php` | `CertificateService` |
| 82 | `pdf/other_certificate.blade.php` | `CertificateService` |
| 83 | `pdf/receipt.blade.php` | `ReceiptService` |
| 84 | `pdf/residency_applicants_report.blade.php` | `ReportController` (DomPDF fallback) |
| 85 | `pdf/minor_violators_report.blade.php` | `ReportController` |
| 86 | `pdf/major_violators_report.blade.php` | `ReportController` (DomPDF fallback) |
| 87 | `pdf/minor_offenses_overall_report.blade.php` | `ReportController` |
| 88 | `pdf/good_moral_applicants_report.blade.php` | `ReportController` (DomPDF fallback) |
| 89 | `pdf/wkhtmltopdf/header.blade.php` | `ReportController` |
| 90 | `pdf/wkhtmltopdf/footer.blade.php` | `ReportController` |
| 91 | `pdf/wkhtmltopdf/residency_applicants_report.blade.php` | `ReportController` |
| 92 | `pdf/wkhtmltopdf/good_moral_applicants_report.blade.php` | `ReportController` |

### Email Views

| # | Blade File | Referenced By |
|---|---|---|
| 93 | `emails/password-reset.blade.php` | `CustomResetPasswordNotification` |
| 94 | `emails/password-reset-text.blade.php` | `CustomResetPasswordNotification` |

### Layout Views

| # | Blade File | Used As |
|---|---|---|
| 95 | `layouts/guest.blade.php` | `<x-guest-layout>` via `GuestLayout` component class |

### Component Blade Files

| # | Blade File | Component Tag |
|---|---|---|
| 96 | `components/dashboard-layout.blade.php` | `<x-dashboard-layout>` |
| 97 | `components/admin-navigation.blade.php` | `<x-admin-navigation>` |
| 98 | `components/dean-navigation.blade.php` | `<x-dean-navigation>` |
| 99 | `components/moderator-navigation.blade.php` | `<x-moderator-navigation>` |
| 100 | `components/psg-officer-navigation.blade.php` | `<x-psg-officer-navigation>` |
| 101 | `components/prog-coor-navigation.blade.php` | `<x-prog-coor-navigation>` |
| 102 | `components/registrar-navigation.blade.php` | `<x-registrar-navigation>` |
| 103 | `components/student-navigation.blade.php` | `<x-student-navigation>` |
| 104 | `components/alumni-navigation.blade.php` | `<x-alumni-navigation>` |
| 105 | `components/icon.blade.php` | `<x-icon>` |
| 106 | `components/icon-logout.blade.php` | `<x-icon-logout>` |
| 107 | `components/text-input.blade.php` | `<x-text-input>` |
| 108 | `components/input-label.blade.php` | `<x-input-label>` |
| 109 | `components/input-error.blade.php` | `<x-input-error>` |
| 110 | `components/primary-button.blade.php` | `<x-primary-button>` |
| 111 | `components/secondary-button.blade.php` | `<x-secondary-button>` |
| 112 | `components/danger-button.blade.php` | `<x-danger-button>` |
| 113 | `components/modal.blade.php` | `<x-modal>` |
| 114 | `components/dropdown.blade.php` | `<x-dropdown>` |
| 115 | `components/dropdown-link.blade.php` | `<x-dropdown-link>` |
| 116 | `components/nav-link.blade.php` | `<x-nav-link>` |
| 117 | `components/responsive-nav-link.blade.php` | `<x-responsive-nav-link>` |
| 118 | `components/auth-session-status.blade.php` | `<x-auth-session-status>` |
| 119 | `components/application-logo.blade.php` | `<x-application-logo>` |
| 120 | `components/sec-osa-navigation.blade.php` | `<x-sec-osa-navigation>` |
| 121 | `components/shared/modals/confirm-action.blade.php` | `<x-shared.modals.confirm-action>` |

### Vendor Views (Laravel framework)

| # | Blade File |
|---|---|
| 122–133 | `vendor/pagination/*.blade.php` (10 files: tailwind, simple-tailwind, simple-default, simple-bootstrap-5, simple-bootstrap-4, semantic-ui, default, custom, bootstrap-5, bootstrap-4) |

---

## 7. Issues Found

### ⚠️ Missing Blade File

| View Component Class | Expected View | Status |
|---|---|---|
| `App\View\Components\IconUserGroup` | `components/icon-user-group.blade.php` | **FILE DOES NOT EXIST** |

The `IconUserGroup` component class at `app/View/Components/IconUserGroup.php` returns `view('components.icon-user-group')`, but no corresponding blade file exists. Any use of `<x-icon-user-group>` will throw a runtime error.

### ⚠️ Potentially Orphaned Views

These blade files exist but have **no direct `view()` call, `@include`, or `<x-component>` reference** found in controllers or other blade files during this audit:

| Blade File | Notes |
|---|---|
| `sec_osa/minor-detail.blade.php` | The `showMinorDetail()` method returns **JSON**, not this view. This file may be a leftover from a previous implementation. |
| `admin/academic-year/history.blade.php` | No controller method returns `admin.academic-year.history`. Only `index` is referenced. May be loaded via AJAX or from within the index view. |
| `components/sec-osa-navigation.blade.php` | All sec_osa views use `<x-moderator-navigation>` instead. This component appears unused. |

### ⚠️ Component Files Possibly Only Used Inside Other Components

These component blade files were not found in the `<x-*>` search of page views, but may be used *inside* other component blade files (e.g., inside `dashboard-layout.blade.php` or navigation components):

- `components/modal.blade.php`
- `components/dropdown.blade.php`
- `components/dropdown-link.blade.php`
- `components/nav-link.blade.php`
- `components/responsive-nav-link.blade.php`
- `components/application-logo.blade.php`
- `components/secondary-button.blade.php`
- `components/danger-button.blade.php`

*(These likely ARE used inside layout/navigation components but were not in the search scope of page-level blade files.)*

---

## 8. Summary Statistics

| Category | Count |
|---|---|
| **Total Blade Files** | 133 |
| Page views (controller-rendered) | 69 |
| Partial/include views | 9 |
| PDF views | 14 |
| Email views | 2 |
| Layout views | 1 |
| Component blade files | 24 |
| Vendor pagination views | 10 |
| Potentially orphaned | 3 |
| Missing blade files | 1 (`icon-user-group`) |
| View component PHP classes | 4 |
| Controllers with `view()` calls | 32 |
| Unique `view()` references | ~75 |
