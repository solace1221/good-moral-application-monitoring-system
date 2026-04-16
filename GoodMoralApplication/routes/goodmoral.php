<?php

use App\Http\Controllers\GoodMoral\ApplicationController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DatabaseSummaryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EscalationController;
use App\Http\Controllers\Admin\StudentOfficerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\ViolatorController;
use App\Http\Controllers\Dean\DashboardController as DeanDashboardController;
use App\Http\Controllers\Dean\ApplicationController as DeanApplicationController;
use App\Http\Controllers\Dean\NotificationController as DeanNotificationController;
use App\Http\Controllers\SecOSA\DashboardController as SecOSADashboardController;
use App\Http\Controllers\SecOSA\ViolationController as SecOSAViolationController;
use App\Http\Controllers\SecOSA\CertificateController as SecOSACertificateController;
use App\Http\Controllers\SecOSA\NotificationController as SecOSANotificationController;
use App\Http\Controllers\SecOSA\ViolatorController as SecOSAViolatorController;
use App\Http\Controllers\GoodMoral\StudentOfficerApplicationController;
use App\Http\Controllers\GoodMoral\PsgOfficerController;
use App\Http\Controllers\GoodMoral\RegistrarController;
use App\Http\Controllers\GoodMoral\ProgramCoordinatorController;
use App\Http\Controllers\Auth\RegisterViolationController;
use App\Http\Controllers\Auth\RegisteredAccountController;
use Illuminate\Support\Facades\Route;

// ─── Student / Application ────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('dashboard');
    Route::get('/notification', [ApplicationController::class, 'notification'])->name('notification');
    Route::get('/notificationViolation', [ApplicationController::class, 'notificationViolation'])->name('notificationViolation');
    Route::get('/student/notification-counts', [ApplicationController::class, 'getNotificationCounts'])->name('student.notification.counts');
    Route::post('/apply/good-moral-certificate', [ApplicationController::class, 'applyForGoodMoralCertificate'])->name('apply.good_moral_certificate');
    Route::post('/receipt/upload', [ApplicationController::class, 'upload'])->name('receipt.upload');

});

// ─── Student Officer Application (students only) ─────────────────────────────
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/apply-student-officer', [StudentOfficerApplicationController::class, 'showForm'])->name('student.applyOfficer');
    Route::post('/student/apply-student-officer', [StudentOfficerApplicationController::class, 'apply'])->name('student.submitOfficerApplication');
    Route::get('/student/officer-positions/{organizationId}', [StudentOfficerApplicationController::class, 'getPositions'])->name('student.officerPositions');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/notification-counts', [NotificationController::class, 'getNotificationCounts'])->name('admin.notificationCounts');
    Route::get('/admin/Application', [AdminApplicationController::class, 'applicationDashboard'])->name('admin.Application');
    Route::get('/admin/ready-for-print', [AdminApplicationController::class, 'readyForPrintApplications'])->name('admin.readyForPrintApplications');
    Route::post('/admin/print-certificate/{id}', [AdminApplicationController::class, 'printCertificate'])->name('admin.printCertificate');
    Route::get('/admin/download-certificate/{id}', [AdminApplicationController::class, 'downloadCertificate'])->name('admin.downloadCertificate');
    Route::post('/admin/certificate/{id}/mark-claimed', [AdminApplicationController::class, 'markAsClaimed'])->name('admin.markAsClaimed');
    Route::patch('/admin/good-moral/{id}/approve', [AdminApplicationController::class, 'approveGoodMoralApplication'])->name('admin.approveGoodMoralApplication');
    Route::patch('/admin/good-moral/{id}/reject', [AdminApplicationController::class, 'rejectGoodMoralApplication'])->name('admin.rejectGoodMoralApplication');
    Route::get('/admin/student-officer-applications', [StudentOfficerController::class, 'index'])->name('admin.studentOfficerApplications');
    Route::get('/admin/generate-violators-report', [ReportController::class, 'generateViolatorsReport'])->name('admin.generateViolatorsReport');
    Route::get('/admin/reports', [ReportController::class, 'reportsPage'])->name('admin.reports');
    Route::get('/admin/reports/history', [ReportController::class, 'reportsHistory'])->name('admin.reports.history');
    Route::post('/admin/generate-selected-report', [ReportController::class, 'generateSelectedReport'])->name('admin.generateSelectedReport');
    Route::patch('/admin/student-officer-applications/{id}/approve', [StudentOfficerController::class, 'approve'])->name('admin.approveOfficer');
    Route::delete('/admin/student-officer-applications/{id}/reject', [StudentOfficerController::class, 'reject'])->name('admin.rejectOfficer');
    Route::patch('/admin/student-officer-applications/{id}/revoke', [StudentOfficerController::class, 'revoke'])->name('admin.revokeOfficer');
    Route::patch('/admin/student-officer-applications/{id}/reconsider', [StudentOfficerController::class, 'reconsider'])->name('admin.reconsiderOfficer');
    Route::post('/admin/AddViolation', [ViolationController::class, 'create'])->name('admin.storeViolation');
    Route::delete('/admin/Addviolation/{id}/delete', [ViolationController::class, 'deleteViolation'])->name('admin.deleteViolation');
    Route::patch('/admin/violation/{id}/archive', [ViolationController::class, 'archiveViolation'])->name('admin.archiveViolation');
    Route::patch('/admin/violation/{id}/restore', [ViolationController::class, 'restoreViolation'])->name('admin.restoreViolation');
    Route::patch('/admin/violation/update/{id}', [ViolationController::class, 'updateViolation'])->name('admin.updateViolation');
    Route::get('/admin/GMAApporvedByRegistrar', [AdminApplicationController::class, 'GMAApporvedByRegistrar'])->name('admin.GMAApporvedByRegistrar');
    Route::post('/admin/import-users', [AccountController::class, 'importUsers'])->name('admin.importUsers');
    Route::get('/admin/account/{id}/edit', [AccountController::class, 'editAccount'])->name('admin.editAccount');
    Route::put('/admin/account/{id}/update', [AccountController::class, 'updateAccount'])->name('admin.updateAccount');
    Route::delete('/admin/account/{id}/delete', [AccountController::class, 'deleteAccount'])->name('admin.deleteAccount');
    Route::post('/admin/account/{id}/convert-to-alumni', [AccountController::class, 'convertToAlumni'])->name('admin.convertToAlumni');
    Route::get('/admin/download-template', [AccountController::class, 'downloadTemplate'])->name('admin.downloadTemplate');
    Route::get('/admin/violation', [ViolationController::class, 'violation'])->name('admin.violation');
    Route::get('/admin/violation/search', [ViolationController::class, 'violationsearch'])->name('admin.violationsearch');
    Route::post('/admin/violation/{id}/close-case', [ViolationController::class, 'closeCase'])->name('violations.closeCase');
    Route::post('/admin/violation/{id}/decline-case', [ViolationController::class, 'declineCase'])->name('violations.declineCase');
    Route::post('/admin/violation/{id}/mark-downloaded', [ViolationController::class, 'markDownloaded'])->name('violations.markDownloaded');
    Route::get('/admin/violation/{id}/download-proceedings', [ViolationController::class, 'downloadProceedings'])->name('admin.downloadProceedings');
    Route::get('/admin/violation-details/{id}', [ViolationController::class, 'getViolationDetails'])->name('admin.violationDetails');
    Route::post('/admin/notification/{id}/mark-read', [EscalationController::class, 'markNotificationAsRead'])->name('admin.markNotificationAsRead');
    Route::patch('/admin/application/{id}/approve', [AdminApplicationController::class, 'approveGMA'])->name('admin.approveGMA');
    Route::delete('/admin/application/{id}/reject', [AdminApplicationController::class, 'rejectGMA'])->name('admin.rejectGMA');
    Route::get('/admin/AddViolation', [ViolationController::class, 'AddViolationDashboard'])->name('admin.AddViolation');
    Route::get('/admin/AddViolator', [ViolatorController::class, 'AddViolatorDashboard'])->name('admin.AddViolator');
    Route::get('/admin/escalation-notifications', [EscalationController::class, 'escalationNotifications'])->name('admin.escalationNotifications');
    Route::post('/admin/trigger-escalation/{student_id}', [EscalationController::class, 'triggerManualEscalation'])->name('admin.triggerEscalation');
    Route::post('/admin/AddViolator', [ViolatorController::class, 'storeViolator'])->name('admin.storeViolator');
    Route::get('/admin/AddMultipleViolators', [ViolatorController::class, 'addMultipleViolatorsForm'])->name('admin.AddMultipleViolators');
    Route::post('/admin/AddMultipleViolators', [ViolatorController::class, 'storeMultipleViolators'])->name('admin.storeMultipleViolators');
    Route::get('/admin/AddAccount', [RegisteredAccountController::class, 'create'])->name('admin.AddAccount');
    Route::get('/admin/database-summary', [DatabaseSummaryController::class, 'databaseSummary'])->name('admin.databaseSummary');
    Route::get('/admin/database-summary/pdf', [DatabaseSummaryController::class, 'downloadDatabaseSummaryPDF'])->name('admin.downloadDatabaseSummaryPDF');
    Route::get('/admin/database-summary/excel', [DatabaseSummaryController::class, 'downloadDatabaseSummaryExcel'])->name('admin.downloadDatabaseSummaryExcel');
});

// ─── PSG Officer ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:psg_officer'])->group(function () {
    Route::get('/PsgOfficer/dashboard', [PsgOfficerController::class, 'dashboard'])->name('PsgOfficer.dashboard');
    Route::get('/PsgOfficer/good-moral-form', [PsgOfficerController::class, 'showGoodMoralForm'])->name('PsgOfficer.goodMoralForm');
    Route::post('/PsgOfficer/apply-good-moral', [PsgOfficerController::class, 'applyForGoodMoral'])->name('PsgOfficer.applyGoodMoral');
    Route::get('/PsgOfficer/personal-violations', [PsgOfficerController::class, 'showPersonalViolations'])->name('PsgOfficer.personalViolations');
    Route::get('/PsgOfficer/applications', [PsgOfficerController::class, 'showApplications'])->name('PsgOfficer.applications');
    Route::get('/PsgOfficer/PsgAddViolation', [RegisterViolationController::class, 'ViolatorDashboard'])->name('PsgOfficer.PsgAddViolation');
    Route::get('/PsgOfficer/Violator', [RegisterViolationController::class, 'violator'])->name('PsgOfficer.Violator');
    Route::get('/psg-officer/check-violations/{studentId}', [RegisterViolationController::class, 'checkStudentViolations'])->name('PsgOfficer.checkViolations');
    Route::post('/PsgOfficer/registerviolation', [RegisterViolationController::class, 'store'])->name('psg.registerviolation');
});

// ─── Registrar ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:registrar'])->group(function () {
    Route::get('/registrar/good-moral-application', [RegistrarController::class, 'goodMoralApplication'])->name('registrar.goodMoralApplication');
    Route::patch('/registrar/application/{id}/approve', [RegistrarController::class, 'approve'])->name('registrar.approve');
    Route::patch('/registrar/reject/{id}', [RegistrarController::class, 'reject'])->name('registrar.reject');
    Route::patch('/registrar/reconsider/{id}', [RegistrarController::class, 'reconsider'])->name('registrar.reconsider');
    Route::get('/registrar/application/{id}/details', [RegistrarController::class, 'getApplicationDetails'])->name('registrar.application.details');
    Route::get('/registrar/notification-counts', [RegistrarController::class, 'getNotificationCounts'])->name('registrar.notificationCounts');
});

// ─── Sec OSA ──────────────────────────────────────────────────────────────────
// Certificate printing routes (admin + sec_osa)
Route::middleware(['auth', 'verified', 'role:admin,sec_osa'])->group(function () {
    Route::post('/moderator/print-certificate/{id}', [SecOSACertificateController::class, 'printCertificate'])->name('moderator.printCertificate');
    Route::get('/moderator/download-certificate/{id}', [SecOSACertificateController::class, 'downloadCertificate'])->name('moderator.downloadCertificate');
    Route::post('/moderator/certificate/{id}/mark-claimed', [SecOSACertificateController::class, 'markAsClaimed'])->name('moderator.markAsClaimed');
});

Route::middleware(['auth', 'verified', 'role:sec_osa'])->group(function () {
    // Dashboard
    Route::get('/sec_osa/dashboard', [SecOSADashboardController::class, 'dashboard'])->name('sec_osa.dashboard');

    // Certificates & Applications
    Route::get('/sec_osa/application', [SecOSACertificateController::class, 'application'])->name('sec_osa.application');

    // Add Violator
    Route::get('/sec_osa/add-violator', [SecOSAViolatorController::class, 'addViolatorForm'])->name('sec_osa.addViolator');
    Route::post('/sec_osa/add-violator', [SecOSAViolatorController::class, 'storeViolator'])->name('sec_osa.storeViolator');

    // Violations
    Route::get('/sec_osa/minor', [SecOSAViolationController::class, 'minor'])->name('sec_osa.minor');
    Route::get('/sec_osa/minor/search', [SecOSAViolationController::class, 'searchMinor'])->name('sec_osa.searchMinor');
    Route::get('/sec_osa/minor/{id}', [SecOSAViolationController::class, 'showMinorDetail'])->name('sec_osa.minorDetail');
    Route::get('/sec_osa/major', [SecOSAViolationController::class, 'major'])->name('sec_osa.major');
    Route::get('/sec_osa/major/search', [SecOSAViolationController::class, 'searchMajor'])->name('sec_osa.searchMajor');
    Route::get('/sec_osa/violations', [SecOSAViolationController::class, 'violation'])->name('sec_osa.violation');
    Route::get('/sec_osa/department/{department}/violations', [SecOSAViolationController::class, 'viewDepartmentViolations'])->name('sec_osa.viewDepartmentViolations');
    Route::get('/sec_osa/escalation-notifications', [SecOSAViolationController::class, 'escalationNotifications'])->name('sec_osa.escalationNotifications');
    Route::post('/sec_osa/upload/{id}', [SecOSAViolationController::class, 'uploadDocument'])->name('sec_osa.document');
    Route::post('/sec_osa/major/{id}/forward-to-admin', [SecOSAViolationController::class, 'forwardToAdmin'])->name('sec_osa.forwardToAdmin');
    Route::get('/sec_osa/major/{id}/upload-proceedings', [SecOSAViolationController::class, 'showUploadProceedings'])->name('sec_osa.showUploadProceedings');
    Route::post('/sec_osa/major/{id}/upload-proceedings', [SecOSAViolationController::class, 'uploadProceedings'])->name('sec_osa.uploadProceedings');
    Route::get('/sec_osa/major/{id}/download-proceedings', [SecOSAViolationController::class, 'downloadProceedings'])->name('sec_osa.downloadProceedings');

    // Notifications
    Route::get('/sec_osa/notification-counts', [SecOSANotificationController::class, 'getNotificationCounts'])->name('sec_osa.notificationCounts');
});

// ─── Dean ─────────────────────────────────────────────────────────────────────
Route::prefix('dean')->name('dean.')->middleware(['auth', 'verified', 'role:dean,deansom,deangradsch'])->group(function () {
    Route::get('/dashboard', [DeanDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/application', [DeanApplicationController::class, 'application'])->name('application');
    Route::patch('/application/{id}/approve', [DeanApplicationController::class, 'approve'])->name('approve');
    Route::delete('/application/{id}/reject', [DeanApplicationController::class, 'reject'])->name('reject');
    Route::get('/notification-counts', [DeanNotificationController::class, 'getNotificationCounts'])->name('notificationCounts');
    Route::patch('/good-moral/{id}/approve', [DeanApplicationController::class, 'approveGoodMoral'])->name('approveGoodMoral');
    Route::patch('/good-moral/{id}/reject', [DeanApplicationController::class, 'rejectGoodMoral'])->name('rejectGoodMoral');
    Route::patch('/reconsider/{id}', [DeanApplicationController::class, 'reconsider'])->name('reconsider');
    Route::get('/application/{id}/details', [DeanApplicationController::class, 'getApplicationDetails'])->name('application.details');
    Route::get('/major', [DeanDashboardController::class, 'major'])->name('major');
    Route::get('/minor', [DeanDashboardController::class, 'minor'])->name('minor');
    Route::post('/violation/{id}/approve', [DeanApplicationController::class, 'deanviolationapprove'])->name('violation.approve');
    Route::post('/violation/{id}/decline', [DeanApplicationController::class, 'deanviolationdecline'])->name('violation.decline');
});

// ─── Program Coordinator ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:prog_coor'])->group(function () {
    Route::get('/prog_coor/major', [ProgramCoordinatorController::class, 'major'])->name('prog_coor.major');
    Route::get('/prog_coor/minor', [ProgramCoordinatorController::class, 'minor'])->name('prog_coor.minor');
    Route::post('/prog_coor/minor/{id}/approve', [ProgramCoordinatorController::class, 'approveMinorViolation'])->name('prog_coor.minor.approve');
    Route::post('/prog_coor/minor/{id}/decline', [ProgramCoordinatorController::class, 'declineMinorViolation'])->name('prog_coor.minor.decline');
    Route::get('/prog_coor/major/{id}/download-proceedings', [ProgramCoordinatorController::class, 'downloadProceedings'])->name('prog_coor.downloadProceedings');
    Route::get('/prog_coor/major/search', [ProgramCoordinatorController::class, 'CoorMajorSearch'])->name('CoorMajorSearch');
});