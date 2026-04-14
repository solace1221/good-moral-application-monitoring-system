<?php

use App\Http\Controllers\Admin\ViolatorController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Shared\OrganizationController;
use App\Http\Controllers\Shared\PositionController;
use App\Http\Controllers\Shared\DepartmentController;
use App\Http\Controllers\Shared\CourseController;
use Illuminate\Support\Facades\Route;

// ─── Admin Management (organizations, positions, courses, departments) ────────
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('organizations', OrganizationController::class);
    Route::resource('positions', PositionController::class);

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');


});

// ─── API ──────────────────────────────────────────────────────────────────────
Route::get('/api/courses', [CourseController::class, 'apiGetCourses'])->name('api.courses');

Route::get('/api/students/search', [ViolatorController::class, 'searchStudents'])
    ->middleware(['auth', 'role:admin,sec_osa,psg_officer'])
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
});

Route::post('/profile/graduation-status', [ProfileController::class, 'updateGraduationStatus'])
    ->middleware(['auth', 'verified'])->name('profile.graduation.update');

Route::post('/profile/convert-to-alumni', [ProfileController::class, 'convertToAlumni'])
    ->middleware(['auth', 'verified'])->name('profile.convert.alumni');
