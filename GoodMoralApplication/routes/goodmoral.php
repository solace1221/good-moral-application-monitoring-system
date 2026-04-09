<?php

use App\Http\Controllers\GoodMoral\ApplicationController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DatabaseSummaryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EscalationController;
use App\Http\Controllers\Admin\PsgController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\ViolatorController;
use App\Http\Controllers\GoodMoral\DeanController;
use App\Http\Controllers\GoodMoral\HeadOSAController;
use App\Http\Controllers\GoodMoral\SecOSAController;
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
    Route::get('/student/profile', [ApplicationController::class, 'profile'])->name('student.profile');
    Route::patch('/student/profile/password', [ApplicationController::class, 'updatePassword'])->name('student.profile.password.update');
    Route::patch('/student/profile/email', [ApplicationController::class, 'updateEmail'])->name('student.profile.email.update');
    Route::patch('/student/profile', [ApplicationController::class, 'updateProfile'])->name('student.profile.update');
    Route::post('/apply/good-moral-certificate', [ApplicationController::class, 'applyForGoodMoralCertificate'])->name('apply.good_moral_certificate');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/notification-counts', [NotificationController::class, 'getNotificationCounts'])->name('admin.notificationCounts');
    Route::get('/admin/Application', [AdminApplicationController::class, 'applicationDashboard'])->name('admin.Application');
    Route::get('/admin/ready-for-print', [AdminApplicationController::class, 'readyForPrintApplications'])->name('admin.readyForPrintApplications');
    Route::post('/admin/print-certificate/{id}', [AdminApplicationController::class, 'printCertificate'])->name('admin.printCertificate');
    Route::get('/admin/download-certificate/{id}', [AdminApplicationController::class, 'downloadCertificate'])->name('admin.downloadCertificate');
    Route::patch('/admin/good-moral/{id}/approve', [AdminApplicationController::class, 'approveGoodMoralApplication'])->name('admin.approveGoodMoralApplication');
    Route::patch('/admin/good-moral/{id}/reject', [AdminApplicationController::class, 'rejectGoodMoralApplication'])->name('admin.rejectGoodMoralApplication');
    Route::get('/admin/psgApplication', [PsgController::class, 'psgApplication'])->name('admin.psgApplication');
    Route::get('/admin/generate-violators-report', [ReportController::class, 'generateViolatorsReport'])->name('admin.generateViolatorsReport');
    Route::get('/admin/reports', [ReportController::class, 'reportsPage'])->name('admin.reports');
    Route::get('/admin/reports/history', [ReportController::class, 'reportsHistory'])->name('admin.reports.history');
    Route::post('/admin/generate-selected-report', [ReportController::class, 'generateSelectedReport'])->name('admin.generateSelectedReport');
    Route::patch('/admin/psgApplication/{student_id}/approve', [PsgController::class, 'approvepsg'])->name('admin.approvepsg');
    Route::delete('/admin/psgApplication/{student_id}/reject', [PsgController::class, 'rejectpsg'])->name('admin.rejectpsg');
    Route::patch('/admin/psgApplication/{student_id}/revoke', [PsgController::class, 'revokePsg'])->name('admin.revokepsg');
    Route::patch('/admin/psgApplication/{student_id}/reconsider', [PsgController::class, 'reconsiderPsg'])->name('admin.reconsiderpsg');
    Route::delete('/admin/Addviolation/{id}/delete', [ViolationController::class, 'deleteViolation'])->name('admin.deleteViolation');
    Route::patch('/admin/violation/update/{id}', [ViolationController::class, 'updateViolation'])->name('admin.updateViolation');
    Route::get('/admin/GMAApporvedByRegistrar', [AdminApplicationController::class, 'GMAApporvedByRegistrar'])->name('admin.GMAApporvedByRegistrar');
    Route::post('/admin/import-users', [AccountController::class, 'importUsers'])->name('admin.importUsers');
    Route::get('/admin/account/{id}/edit', [AccountController::class, 'editAccount'])->name('admin.editAccount');
    Route::put('/admin/account/{id}/update', [AccountController::class, 'updateAccount'])->name('admin.updateAccount');
    Route::delete('/admin/account/{id}/delete', [AccountController::class, 'deleteAccount'])->name('admin.deleteAccount');
    Route::get('/admin/download-template', [AccountController::class, 'downloadTemplate'])->name('admin.downloadTemplate');
    Route::get('/admin/violation', [ViolationController::class, 'violation'])->name('admin.violation');
    Route::get('/admin/violation/search', [ViolationController::class, 'violationsearch'])->name('admin.violationsearch');
    Route::post('/admin/violation/{id}/close-case', [ViolationController::class, 'closeCase'])->name('violations.closeCase');
    Route::post('/admin/violation/{id}/mark-downloaded', [ViolationController::class, 'markDownloaded'])->name('violations.markDownloaded');
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
    Route::get('/PsgOfficer/PsgViolation', [RegisterViolationController::class, 'PsgViolation'])->name('PsgOfficer.PsgViolation');
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

// ─── Head OSA ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:head_osa'])->group(function () {
    Route::get('/head_osa/dashboard', [HeadOSAController::class, 'dashboard'])->name('head_osa.dashboard');
    Route::patch('/head_osa/application/{id}/approve', [HeadOSAController::class, 'approve'])->name('head_osa.approve');
    Route::delete('/head_osa/application/{id}/reject', [HeadOSAController::class, 'reject'])->name('head_osa.reject');
});

// ─── Sec OSA ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:sec_osa'])->group(function () {
    Route::get('/sec_osa/dashboard', [SecOSAController::class, 'dashboard'])->name('sec_osa.dashboard');
    Route::get('/sec_osa/application', [SecOSAController::class, 'application'])->name('sec_osa.application');
    Route::patch('/application/{id}/approve', [SecOSAController::class, 'approve'])->name('sec_osa.approve');
    Route::delete('/sec_osa/application/{id}/reject', [SecOSAController::class, 'reject'])->name('sec_osa.reject');
    Route::get('/sec_osa/minor', [SecOSAController::class, 'minor'])->name('sec_osa.minor');
    Route::get('/sec_osa/major', [SecOSAController::class, 'major'])->name('sec_osa.major');
    Route::get('/sec_osa/escalation-notifications', [SecOSAController::class, 'escalationNotifications'])->name('sec_osa.escalationNotifications');
    Route::get('/sec_osa/notification-counts', [SecOSAController::class, 'getNotificationCounts'])->name('sec_osa.notificationCounts');
    Route::post('/sec_osa/upload/{id}', [SecOSAController::class, 'uploadDocument'])->name('sec_osa.document');
    Route::post('/sec_osa/major/{id}/forward-to-admin', [SecOSAController::class, 'forwardToAdmin'])->name('sec_osa.forwardToAdmin');
    Route::post('/moderator/print-certificate/{id}', [SecOSAController::class, 'printCertificate'])->name('moderator.printCertificate');
    Route::get('/moderator/download-certificate/{id}', [SecOSAController::class, 'downloadCertificate'])->name('moderator.downloadCertificate');
    Route::get('/sec_osa/department/{department}/violations', [SecOSAController::class, 'viewDepartmentViolations'])->name('sec_osa.viewDepartmentViolations');
    Route::get('/sec_osa/violations', [SecOSAController::class, 'violation'])->name('sec_osa.violation');
    Route::get('/sec_osa/profile', [SecOSAController::class, 'profile'])->name('sec_osa.profile');
    Route::patch('/sec_osa/profile', [SecOSAController::class, 'updateProfile'])->name('sec_osa.profile.update');
    Route::patch('/sec_osa/profile/email', [SecOSAController::class, 'updateEmail'])->name('sec_osa.profile.email.update');
    Route::patch('/sec_osa/profile/password', [SecOSAController::class, 'updatePassword'])->name('sec_osa.profile.password.update');
    Route::get('/sec_osa/major/{id}/upload-proceedings', [SecOSAController::class, 'showUploadProceedings'])->name('sec_osa.showUploadProceedings');
    Route::post('/sec_osa/major/{id}/upload-proceedings', [SecOSAController::class, 'uploadProceedings'])->name('sec_osa.uploadProceedings');
    Route::get('/sec_osa/major/{id}/download-proceedings', [SecOSAController::class, 'downloadProceedings'])->name('sec_osa.downloadProceedings');
    Route::get('/sec_osa/minor/search', [SecOSAController::class, 'searchMinor'])->name('sec_osa.searchMinor');
    Route::get('/sec_osa/major/search', [SecOSAController::class, 'searchMajor'])->name('sec_osa.searchMajor');
});

// ─── Dean ─────────────────────────────────────────────────────────────────────
Route::prefix('dean')->name('dean.')->middleware(['auth', 'verified', 'role:dean,deansom,deangradsch'])->group(function () {
    Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
    Route::get('/application', [DeanController::class, 'application'])->name('application');
    Route::patch('/application/{id}/approve', [DeanController::class, 'approve'])->name('approve');
    Route::delete('/application/{id}/reject', [DeanController::class, 'reject'])->name('reject');
    Route::get('/notification-counts', [DeanController::class, 'getNotificationCounts'])->name('notificationCounts');
    Route::patch('/good-moral/{id}/approve', [DeanController::class, 'approveGoodMoral'])->name('approveGoodMoral');
    Route::patch('/good-moral/{id}/reject', [DeanController::class, 'rejectGoodMoral'])->name('rejectGoodMoral');
    Route::patch('/reconsider/{id}', [DeanController::class, 'reconsider'])->name('reconsider');
    Route::get('/application/{id}/details', [DeanController::class, 'getApplicationDetails'])->name('application.details');
    Route::get('/major', [DeanController::class, 'major'])->name('major');
    Route::get('/minor', [DeanController::class, 'minor'])->name('minor');
    Route::post('/violation/{id}/approve', [DeanController::class, 'deanviolationapprove'])->name('violation.approve');
});

// ─── Program Coordinator ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:prog_coor'])->group(function () {
    Route::get('/prog_coor/major', [ProgramCoordinatorController::class, 'major'])->name('prog_coor.major');
    Route::get('/prog_coor/major/{id}/download-proceedings', [ProgramCoordinatorController::class, 'downloadProceedings'])->name('prog_coor.downloadProceedings');
    Route::get('/prog_coor/major/search', [ProgramCoordinatorController::class, 'CoorMajorSearch'])->name('CoorMajorSearch');
});