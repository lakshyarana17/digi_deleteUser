<?php

use Illuminate\Support\Facades\Route;
use Lakshya\AccountDeletion\Http\Controllers\AccountController;

Route::post('/account/delete/request', [AccountController::class, 'requestDeletion']);
Route::post('/account/delete/confirm', [AccountController::class, 'confirmDeletion']);
Route::post('/account/delete/resend', [AccountController::class, 'resendOtp']);
