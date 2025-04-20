<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

// Dashboard Routes are defined below in the middleware group

// Authentication Routes
Auth::routes();

// Doctor Routes
Route::resource('doctors', App\Http\Controllers\DoctorController::class);
Route::get('/doctors/search', [App\Http\Controllers\DoctorController::class, 'search'])->name('doctors.search');

// Patient Routes
Route::resource('patients', App\Http\Controllers\PatientController::class);
Route::get('/patients/{patient}/medical-history', [App\Http\Controllers\PatientController::class, 'medicalHistory'])->name('patients.medical-history');
Route::post('/patients/{patient}/medical-history', [App\Http\Controllers\PatientController::class, 'updateMedicalHistory'])->name('patients.update-medical-history');

// Appointment Routes
Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
Route::get('/search-doctors', [App\Http\Controllers\AppointmentController::class, 'searchDoctors'])->name('appointments.search-doctors');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // Doctor Routes
    Route::middleware([\App\Http\Middleware\DoctorMiddleware::class])->prefix('doctor')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'doctorDashboard'])->name('doctor.dashboard');
    });

    // Patient Routes
    Route::middleware([\App\Http\Middleware\PatientMiddleware::class])->prefix('patient')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'patientDashboard'])->name('patient.dashboard');
    });
});
