<?php

use Illuminate\Support\Facades\Route;
use YourVendor\AccountDeletion\Http\Controllers\AccountController;

Route::post('/account/delete/request', [AccountController::class, 'requestDeletion']);
Route::post('/account/delete/confirm', [AccountController::class, 'confirmDeletion']);
