<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.attempt');
Route::view('/register', 'admin.auth.register')->name('register');

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');
