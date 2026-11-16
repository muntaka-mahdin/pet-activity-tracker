<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::view('/about', 'about')->name('about');

Route::resource('pets', PetController::class);
Route::resource('activities', ActivityController::class);
