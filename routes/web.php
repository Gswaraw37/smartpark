<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\VisitorController;

Route::prefix('admin')->middleware(['auth', 'role:admin|staff'])->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index']);

    Route::put('/terima/{parkingSession}/accept', [AdminController::class, 'accept'])->name('admin.parkingSession.accept');
    Route::put('/tolak/{parkingSession}/decline', [AdminController::class, 'decline'])->name('admin.parkingSession.decline');

    Route::get('/profile', [AdminController::class, 'showProfile']);
    Route::put('/profile/{user}/editProfile', [AdminController::class, 'editProfile'])->name('admin.profile.editProfile');

    Route::get('/detail/{id}', [AdminController::class, 'showDetail'])->name('detail.approval');

    Route::get('/lokasi', [AdminController::class, 'showLoc']);

    Route::get('/daftar-staff', [StaffController::class, 'index']);
    Route::post('/daftar-staff/store', [StaffController::class, 'store']);
    Route::get('/daftar-staff/{id}', [StaffController::class, 'show']);
    Route::get('/daftar-staff/edit/{id}', [StaffController::class, 'edit']);
    Route::put('/daftar-staff/{id}', [StaffController::class, 'update']);
    Route::delete('/daftar-staff/{id}', [StaffController::class, 'destroy']);

    Route::get('/daftar-kendaraan/mobil', [CarController::class, 'index']);
    Route::get('/daftar-kendaraan/motor', [MotoController::class, 'index']);

    Route::get('/summary', [SummaryController::class, 'index']);
    Route::get('/summary/export/excel', [SummaryController::class, 'export']);
});

Route::get('/', [VisitorController::class, 'index'])->middleware('auth');
Route::post('/', [VisitorController::class, 'store'])->middleware('auth');

Route::get('/profile', [VisitorController::class, 'showProfile']);
Route::put('/profile/{user}/editProfile', [VisitorController::class, 'editProfile'])->name('visitor.profile.editProfile');

Route::get('/parkir', [VisitorController::class, 'parkir'])->middleware('auth');
Route::post('/parkIn', [VisitorController::class, 'parkIn'])->middleware('auth');
Route::post('/parkOut', [VisitorController::class, 'parkOut'])->middleware('auth');
Route::post('/resubmitParkOut', [VisitorController::class, 'resubmitParkOut'])->middleware('auth')->name('parkir.resubmit');
Route::get('/redirect-after-approval', [VisitorController::class, 'handleRedirectAfterApproval'])->middleware('auth');

Route::get('/lokasi', [VisitorController::class, 'showLocaction'])->middleware('auth');

Route::get('/payments/{sessionId}', [PaymentController::class, 'showPayment'])->name('payments.show');
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])->name('midtrans.callback');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/register', [AuthController::class, 'store']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');