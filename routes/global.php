<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('pin_login', fn () => view('auth.pin_login'));
Route::get('maintenance/mode', fn () => view('maintenance/maintenance'))->name('maintenance.mode');
