<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::match(['GET','POST'], '/users', [AuthController::class, 'register'])->name('register');
    Route::match(['GET','POST'], '/login', [AuthController::class, 'login'])->name('login');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::match(['GET','POST'], '/deposit', [HomeController::class, 'getAllDepositedTransactions'])->name('deposit');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});
