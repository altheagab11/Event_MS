<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminEvaluationsController;
use App\Http\Controllers\AdminParticipantsController;
use App\Http\Controllers\EventEvaluationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\QrAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'landing']);

Route::post('/register/send-verification', [RegistrationController::class, 'sendVerification'])
  ->middleware('throttle:6,1')
  ->name('registration.send-verification');

Route::post('/register/resend-code', [RegistrationController::class, 'resendVerificationCode'])
  ->middleware('throttle:4,1')
  ->name('registration.resend-code');

Route::post('/register/verify-code', [RegistrationController::class, 'verifyCodeAndFinalize'])
  ->middleware('throttle:12,1')
  ->name('registration.verify-code');

Route::post('/events/{event}/evaluate', [EventEvaluationController::class, 'store'])
  ->middleware('throttle:20,1')
  ->name('events.evaluate');

Route::get('/admin-login', [LoginController::class, 'create'])->name('admin.login.page');
Route::get('/login', [LoginController::class, 'create'])->name('admin.login');

Route::middleware('guest')->group(function () {
  Route::post('/login', [LoginController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->group(function () {
  Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

  Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events');
  Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
  Route::put('/admin/events/{event:event_id}', [EventController::class, 'update'])->name('admin.events.update');
  Route::patch('/admin/events/{event:event_id}/archive', [EventController::class, 'archive'])->name('admin.events.archive');
  Route::post('/admin/events/{event:event_id}/send-evaluation-reminder', [EventController::class, 'sendEvaluationReminder'])->name('admin.events.send-evaluation-reminder');

  Route::get('/admin/participants', [AdminParticipantsController::class, 'index'])
    ->name('admin.participants');

  Route::get('/admin/participants/{registration:registration_id}/paper-download', [AdminParticipantsController::class, 'downloadLatestPaper'])
    ->name('admin.participants.paper.download');

  Route::post('/admin/participants/{registration:registration_id}/reject', [AdminParticipantsController::class, 'rejectApplication'])
    ->name('admin.participants.reject');

  Route::post('/admin/participants/{registration:registration_id}/approve', [AdminParticipantsController::class, 'approveAndSendId'])
    ->name('admin.participants.approve');

  Route::get('/admin/evaluations', [AdminEvaluationsController::class, 'index'])
    ->name('admin.evaluations');

  Route::post('/admin/attendance/validate-qr', [QrAttendanceController::class, 'validateAndCheckIn'])
    ->name('admin.attendance.validate-qr');

  Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
