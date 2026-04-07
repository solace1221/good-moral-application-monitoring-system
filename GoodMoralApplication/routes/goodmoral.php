<?php

use App\Http\Controllers\GoodMoral\ApplicationController;
use App\Http\Controllers\GoodMoral\AdminController;
use App\Http\Controllers\GoodMoral\DeanController;
use App\Http\Controllers\GoodMoral\HeadOSAController;
use App\Http\Controllers\GoodMoral\SecOSAController;
use App\Http\Controllers\GoodMoral\PsgOfficerController;
use App\Http\Controllers\GoodMoral\RegistrarController;
use App\Http\Controllers\GoodMoral\ProgramCoordinatorController;
use App\Http\Controllers\Auth\RegisterViolationController;
use Illuminate\Support\Facades\Route;

// ─── Student / Application ────────────────────────────────────────────────────
Route::get('/dashboard', [ApplicationController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/notification', [ApplicationController::class, 'notification'])
    ->middleware(['auth', 'verified'])->name('notification');

Route::get('/notificationViolation', [ApplicationController::class, 'notificationViolation'])
    ->middleware(['auth', 'verified'])->name('notificationViolation');

Route::get('/student/notification-counts', [ApplicationController::class, 'getNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('student.notification.counts');

Route::get('/student/profile', [ApplicationController::class, 'profile'])
    ->middleware(['auth', 'verified'])->name('student.profile');

Route::patch('/student/profile/password', [ApplicationController::class, 'updatePassword'])
    ->middleware(['auth', 'verified'])->name('student.profile.password.update');

Route::patch('/student/profile/email', [ApplicationController::class, 'updateEmail'])
    ->middleware(['auth', 'verified'])->name('student.profile.email.update');

Route::patch('/student/profile', [ApplicationController::class, 'updateProfile'])
    ->middleware(['auth', 'verified'])->name('student.profile.update');

Route::post('/apply/good-moral-certificate', [ApplicationController::class, 'applyForGoodMoralCertificate'])
    ->name('apply.good_moral_certificate');

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');

Route::get('/admin/notification-counts', [AdminController::class, 'getNotificationCounts'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.notificationCounts');

Route::get('/admin/Application', [AdminController::class, 'applicationDashboard'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.Application');

Route::patch('/admin/Application/{id}/approve', [AdminController::class, 'approveApplication'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.approveApplication');

Route::delete('/admin/Application/{id}/reject', [AdminController::class, 'rejectApplication'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.rejectApplication');

Route::get('/admin/ready-for-print', [AdminController::class, 'readyForPrintApplications'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.readyForPrintApplications');

Route::post('/admin/print-certificate/{id}', [AdminController::class, 'printCertificate'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.printCertificate');

Route::get('/admin/download-certificate/{id}', [AdminController::class, 'downloadCertificate'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.downloadCertificate');

Route::patch('/admin/good-moral/{id}/approve', [AdminController::class, 'approveGoodMoralApplication'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.approveGoodMoralApplication');

Route::patch('/admin/good-moral/{id}/reject', [AdminController::class, 'rejectGoodMoralApplication'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.rejectGoodMoralApplication');

Route::get('/admin/psgApplication', [AdminController::class, 'psgApplication'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.psgApplication');

Route::get('/admin/generate-violators-report', [AdminController::class, 'generateViolatorsReport'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.generateViolatorsReport');

Route::get('/admin/reports', [AdminController::class, 'reportsPage'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.reports');

Route::get('/admin/reports/history', [AdminController::class, 'reportsHistory'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.reports.history');

Route::post('/admin/generate-selected-report', [AdminController::class, 'generateSelectedReport'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.generateSelectedReport');

Route::patch('/admin/psgApplication/{student_id}/approve', [AdminController::class, 'approvepsg'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.approvepsg');

Route::delete('/admin/psgApplication/{student_id}/reject', [AdminController::class, 'rejectpsg'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.rejectpsg');

Route::patch('/admin/psgApplication/{student_id}/revoke', [AdminController::class, 'revokePsg'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.revokepsg');

Route::patch('/admin/psgApplication/{student_id}/reconsider', [AdminController::class, 'reconsiderPsg'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.reconsiderpsg');

Route::delete('/admin/Addviolation/{id}/delete', [AdminController::class, 'deleteViolation'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.deleteViolation');

Route::patch('/admin/violation/update/{id}', [AdminController::class, 'updateViolation'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.updateViolation');

Route::get('/admin/GMAApporvedByRegistrar', [AdminController::class, 'GMAApporvedByRegistrar'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.GMAApporvedByRegistrar');

Route::post('/admin/import-users', [AdminController::class, 'importUsers'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.importUsers');

Route::get('/admin/account/{id}/edit', [AdminController::class, 'editAccount'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.editAccount');

Route::put('/admin/account/{id}/update', [AdminController::class, 'updateAccount'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.updateAccount');

Route::delete('/admin/account/{id}/delete', [AdminController::class, 'deleteAccount'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.deleteAccount');

Route::get('/admin/download-template', [AdminController::class, 'downloadTemplate'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.downloadTemplate');

Route::get('/admin/violation', [AdminController::class, 'violation'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.violation');

Route::get('/admin/violation/search', [AdminController::class, 'violationsearch'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.violationsearch');

Route::post('/admin/violation/{id}/close-case', [AdminController::class, 'closeCase'])
    ->middleware(['auth', 'verified', 'admin'])->name('violations.closeCase');

Route::post('/admin/violation/{id}/mark-downloaded', [AdminController::class, 'markDownloaded'])
    ->middleware(['auth', 'verified', 'admin'])->name('violations.markDownloaded');

Route::get('/admin/violation-details/{id}', [AdminController::class, 'getViolationDetails'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.violationDetails');

Route::post('/admin/notification/{id}/mark-read', [AdminController::class, 'markNotificationAsRead'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.markNotificationAsRead');

Route::patch('/admin/application/{id}/approve', [AdminController::class, 'approveGMA'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.approveGMA');

Route::delete('/admin/application/{id}/reject', [AdminController::class, 'rejectGMA'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.rejectGMA');

Route::get('/admin/AddViolation', [AdminController::class, 'AddViolationDashboard'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.AddViolation');

Route::get('/admin/AddViolator', [AdminController::class, 'AddViolatorDashboard'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.AddViolator');

Route::get('/admin/escalation-notifications', [AdminController::class, 'escalationNotifications'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.escalationNotifications');

Route::post('/admin/trigger-escalation/{student_id}', [AdminController::class, 'triggerManualEscalation'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.triggerEscalation');

Route::post('/admin/AddViolator', [AdminController::class, 'storeViolator'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.storeViolator');

Route::get('/admin/AddMultipleViolators', [AdminController::class, 'addMultipleViolatorsForm'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.AddMultipleViolators');

Route::post('/admin/AddMultipleViolators', [AdminController::class, 'storeMultipleViolators'])
    ->middleware(['auth', 'verified', 'admin'])->name('admin.storeMultipleViolators');

// ─── PSG Officer ──────────────────────────────────────────────────────────────
Route::get('/PsgOfficer/dashboard', [PsgOfficerController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'role:psg_officer'])->name('PsgOfficer.dashboard');

Route::get('/PsgOfficer/good-moral-form', [PsgOfficerController::class, 'showGoodMoralForm'])
    ->middleware(['auth', 'verified', 'role:psg_officer'])->name('PsgOfficer.goodMoralForm');

Route::post('/PsgOfficer/apply-good-moral', [PsgOfficerController::class, 'applyForGoodMoral'])
    ->middleware(['auth', 'verified', 'role:psg_officer'])->name('PsgOfficer.applyGoodMoral');

Route::get('/PsgOfficer/personal-violations', [PsgOfficerController::class, 'showPersonalViolations'])
    ->middleware(['auth', 'verified', 'role:psg_officer'])->name('PsgOfficer.personalViolations');

Route::get('/PsgOfficer/applications', [PsgOfficerController::class, 'showApplications'])
    ->middleware(['auth', 'verified', 'role:psg_officer'])->name('PsgOfficer.applications');

Route::get('/PsgOfficer/PsgAddViolation', [RegisterViolationController::class, 'ViolatorDashboard'])
    ->middleware(['auth', 'verified'])->name('PsgOfficer.PsgAddViolation');

Route::get('/PsgOfficer/Violator', [RegisterViolationController::class, 'violator'])
    ->middleware(['auth', 'verified'])->name('PsgOfficer.Violator');

Route::get('/PsgOfficer/PsgViolation', [RegisterViolationController::class, 'PsgViolation'])
    ->middleware(['auth', 'verified'])->name('PsgOfficer.PsgViolation');

Route::get('/psg-officer/check-violations/{studentId}', [RegisterViolationController::class, 'checkStudentViolations'])
    ->middleware(['auth', 'verified'])->name('PsgOfficer.checkViolations');

Route::post('/PsgOfficer/registerviolation', [RegisterViolationController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('psg.registerviolation');

// ─── Registrar ────────────────────────────────────────────────────────────────
Route::get('/registrar/good-moral-application', [RegistrarController::class, 'goodMoralApplication'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.goodMoralApplication');

Route::patch('/registrar/application/{id}/approve', [RegistrarController::class, 'approve'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.approve');

Route::patch('/registrar/reject/{id}', [RegistrarController::class, 'reject'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.reject');

Route::patch('/registrar/reconsider/{id}', [RegistrarController::class, 'reconsider'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.reconsider');

Route::get('/registrar/application/{id}/details', [RegistrarController::class, 'getApplicationDetails'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.application.details');

Route::get('/registrar/notification-counts', [RegistrarController::class, 'getNotificationCounts'])
    ->middleware(['auth', 'verified', 'role:registrar'])->name('registrar.notificationCounts');

// ─── Head OSA ─────────────────────────────────────────────────────────────────
Route::get('/head_osa/dashboard', [HeadOSAController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'role:head_osa'])->name('head_osa.dashboard');

Route::patch('/head_osa/application/{id}/approve', [HeadOSAController::class, 'approve'])
    ->middleware(['auth', 'verified', 'role:head_osa'])->name('head_osa.approve');

Route::delete('/head_osa/application/{id}/reject', [HeadOSAController::class, 'reject'])
    ->middleware(['auth', 'verified', 'role:head_osa'])->name('head_osa.reject');

// ─── Sec OSA ──────────────────────────────────────────────────────────────────
Route::get('/sec_osa/dashboard', [SecOSAController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.dashboard');

Route::get('/sec_osa/application', [SecOSAController::class, 'application'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.application');

Route::patch('/application/{id}/approve', [SecOSAController::class, 'approve'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.approve');

Route::delete('/sec_osa/application/{id}/reject', [SecOSAController::class, 'reject'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.reject');

Route::get('/sec_osa/minor', [SecOSAController::class, 'minor'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.minor');

Route::get('/sec_osa/major', [SecOSAController::class, 'major'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.major');

Route::get('/sec_osa/escalation-notifications', [SecOSAController::class, 'escalationNotifications'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.escalationNotifications');

Route::get('/sec_osa/notification-counts', [SecOSAController::class, 'getNotificationCounts'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.notificationCounts');

Route::post('/sec_osa/upload/{id}', [SecOSAController::class, 'uploadDocument'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.document');

Route::post('/sec_osa/major/{id}/forward-to-admin', [SecOSAController::class, 'forwardToAdmin'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.forwardToAdmin');

Route::post('/moderator/print-certificate/{id}', [SecOSAController::class, 'printCertificate'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('moderator.printCertificate');

Route::get('/moderator/download-certificate/{id}', [SecOSAController::class, 'downloadCertificate'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('moderator.downloadCertificate');

Route::get('/sec_osa/department/{department}/violations', [SecOSAController::class, 'viewDepartmentViolations'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.viewDepartmentViolations');

Route::get('/sec_osa/violations', [SecOSAController::class, 'violation'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.violation');

Route::get('/sec_osa/profile', [SecOSAController::class, 'profile'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.profile');

Route::patch('/sec_osa/profile', [SecOSAController::class, 'updateProfile'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.profile.update');

Route::patch('/sec_osa/profile/email', [SecOSAController::class, 'updateEmail'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.profile.email.update');

Route::patch('/sec_osa/profile/password', [SecOSAController::class, 'updatePassword'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.profile.password.update');

Route::get('/sec_osa/major/{id}/upload-proceedings', [SecOSAController::class, 'showUploadProceedings'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.showUploadProceedings');

Route::post('/sec_osa/major/{id}/upload-proceedings', [SecOSAController::class, 'uploadProceedings'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.uploadProceedings');

Route::get('/sec_osa/major/{id}/download-proceedings', [SecOSAController::class, 'downloadProceedings'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.downloadProceedings');

Route::get('/sec_osa/minor/search', [SecOSAController::class, 'searchMinor'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.searchMinor');

Route::get('/sec_osa/major/search', [SecOSAController::class, 'searchMajor'])
    ->middleware(['auth', 'verified', 'role:sec_osa'])->name('sec_osa.searchMajor');

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
Route::get('/prog_coor/major', [ProgramCoordinatorController::class, 'major'])
    ->middleware(['auth', 'verified', 'role:prog_coor'])->name('prog_coor.major');

Route::get('/prog_coor/major/{id}/download-proceedings', [ProgramCoordinatorController::class, 'downloadProceedings'])
    ->middleware(['auth', 'verified', 'role:prog_coor'])->name('prog_coor.downloadProceedings');

Route::get('/prog_coor/major/search', [ProgramCoordinatorController::class, 'CoorMajorSearch'])
    ->middleware(['auth', 'verified', 'role:prog_coor'])->name('CoorMajorSearch');
