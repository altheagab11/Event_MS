<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QrAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'landing']);

Route::middleware('guest')->group(function () {
  Route::get('/login', [LoginController::class, 'create'])->name('admin.login');
  Route::post('/login', [LoginController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->group(function () {
  Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
  })->name('admin.dashboard');

  Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events');
  Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');

  Route::get('/admin/participants', function () {
    return view('admin.participants');
  })->name('admin.participants');

  Route::get('/admin/evaluations', function () {
    return view('admin.evaluations');
  })->name('admin.evaluations');

  Route::post('/admin/attendance/validate-qr', [QrAttendanceController::class, 'validateAndCheckIn'])
    ->name('admin.attendance.validate-qr');

  Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
