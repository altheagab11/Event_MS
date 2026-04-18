<?php

use App\Http\Controllers\QrAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('landingpage');
});

Route::get('/login', function () {
  return view('admin-login');
})->name('admin.login');

Route::get('/admin/dashboard', function () {
  return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/events', function () {
  return view('admin.events');
})->name('admin.events');

Route::get('/admin/participants', function () {
  return view('admin.participants');
})->name('admin.participants');

Route::get('/admin/evaluations', function () {
  return view('admin.evaluations');
})->name('admin.evaluations');

Route::post('/admin/attendance/validate-qr', [QrAttendanceController::class, 'validateAndCheckIn'])
  ->name('admin.attendance.validate-qr');
