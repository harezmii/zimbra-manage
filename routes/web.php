<?php


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/token',function (){ return csrf_token(); })->name('token')->name('csrf_token');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


