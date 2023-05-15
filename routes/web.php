<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('admin.service.index');
});

Route::get('admin', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('admin.service.index');
});
