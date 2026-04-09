<?php

use App\Http\Controllers\Admin\ViolatorController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Shared\DesignationController;
use App\Http\Controllers\Shared\PositionController;
use App\Http\Controllers\Shared\CourseController;
use App\Http\Controllers\Shared\AcademicYearController;
use Illuminate\Support\Facades\Route;

// ─── Admin Management (designations, positions, courses, academic years) ─────
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('designations', DesignationController::class);
    Route::resource('positions', PositionController::class);

    Route::get('/courses/upload', [CourseController::class, 'uploadForm'])->name('courses.upload.form');
    Route::post('/courses/upload', [CourseController::class, 'uploadCsv'])->name('courses.upload');
    Route::get('/courses/template', [CourseController::class, 'downloadTemplate'])->name('courses.template');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::patch('/courses/{course}/toggle', [CourseController::class, 'toggleStatus'])->name('courses.toggle');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/academic-years', [AcademicYearController::class, 'index'])->name('academic-year.index');
    Route::post('/academic-years', [AcademicYearController::class, 'store'])->name('academic-year.store');
    Route::get('/academic-years/active', [AcademicYearController::class, 'getActiveYears'])->name('academic-year.active');
    Route::post('/academic-years/{id}/trigger-new-year', [AcademicYearController::class, 'triggerNewYear'])->name('academic-year.trigger-new-year');
    Route::post('/academic-years/process-promotions', [AcademicYearController::class, 'processPromotions'])->name('academic-year.process-promotions');
    Route::post('/academic-years/promote-student', [AcademicYearController::class, 'promoteStudent'])->name('academic-year.promote-student');
    Route::get('/academic-years/history', [AcademicYearController::class, 'history'])->name('academic-year.history');
    Route::get('/academic-years/search-students', [AcademicYearController::class, 'searchStudents'])->name('academic-year.search-students');
});

// ─── API ──────────────────────────────────────────────────────────────────────
Route::get('/api/courses', [CourseController::class, 'apiGetCourses'])->name('api.courses');

Route::get('/api/students/search', [ViolatorController::class, 'searchStudents'])
    ->middleware(['auth', 'verified'])
    ->name('api.students.search');

// ─── Notification Counts (all roles, via ProfileController) ──────────────────
Route::get('/psg-officer/notification-counts', [ProfileController::class, 'getPsgOfficerNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('psg.officer.notification.counts');

Route::get('/sec-osa/notification-counts', [ProfileController::class, 'getSecOsaNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('sec.osa.notification.counts');

Route::get('/head-osa/notification-counts', [ProfileController::class, 'getHeadOsaNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('head.osa.notification.counts');

Route::get('/dean/notification-counts', [ProfileController::class, 'getDeanNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('dean.notification.counts');

Route::get('/registrar/notification-counts', [ProfileController::class, 'getRegistrarNotificationCounts'])
    ->middleware(['auth', 'verified'])->name('registrar.notification.counts');

// ─── Profile ──────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::get('/profile/verify-email/{token}', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email');
    Route::get('/profile/admin', [ProfileController::class, 'adminProfile'])->name('profile.admin');
    Route::patch('/profile/admin', [ProfileController::class, 'updateAdminProfile'])->name('profile.admin.update');
    Route::get('/profile/moderator', [ProfileController::class, 'moderatorProfile'])->name('profile.moderator');
    Route::patch('/profile/moderator', [ProfileController::class, 'updateModeratorProfile'])->name('profile.moderator.update');
});

Route::post('/profile/graduation-status', [ProfileController::class, 'updateGraduationStatus'])
    ->middleware(['auth', 'verified'])->name('profile.graduation.update');

Route::post('/profile/convert-to-alumni', [ProfileController::class, 'convertToAlumni'])
    ->middleware(['auth', 'verified'])->name('profile.convert.alumni');
