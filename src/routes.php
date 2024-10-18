<?php

use Illuminate\Support\Facades\Route;
use Lakshya\AccountDeletion\Http\Controllers\AccountController;

Route::post('/account/delete/request', [AccountController::class, 'requestDeletion']);
Route::post('/account/delete/confirm', [AccountController::class, 'confirmDeletion']);
Route::post('/account/delete/resend', [AccountController::class, 'resendOtp']);

Route::get('/request-otp', function () {
    return view('email.Otp_email'); 
})->name('otp.request.form');

Route::post('/request-otp', [AccountController::class, 'requestDeletion'])->name('otp.request');

Route::get('/confirm-otp', function () {
    return view('email.Otp_email'); 
})->name('otp.confirm.form');

// Route for handling OTP confirmation
Route::post('/confirm-otp', [AccountController::class, 'confirmDeletion'])->name('otp.confirm');

