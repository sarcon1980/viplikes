<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\PermissionController;
use Modules\User\Http\Controllers\Admin\RoleController;
use Modules\User\Http\Controllers\Admin\UserController;
use Modules\User\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['role:Admins'],
], function () {

    Route::resource('/users', UserController::class);
    Route::resource('permissions', PermissionController::class)->except(['create', 'show', 'edit']);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);

});

Route::middleware('auth')->group(function() {

//    Route::get('/', function() {
//        return Inertia::render('Home');
//    })->middleware('verified')->name('home');

    Route::post('user/logout', [AuthController::class, 'logout'])->name('logout');

//    Route::get('/email/verify', [AuthController::class, 'emailVerification'])->middleware('auth')->name('verification.notice');
//
//    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//        $request->fulfill();
//
//        return redirect()->route('home')->with([
//            'message' => 'Email verified successfully',
//            'class' => 'alert alert-success'
//        ]);
//    })->middleware('signed')->name('verification.verify');
//
//    Route::post('/email/verification-notification', function (Request $request) {
//        $request->user()->sendEmailVerificationNotification();
//
//        return redirect()->back()->with([
//            'message' => 'Verification link sent!',
//            'class' => 'alert alert-success'
//        ]);
//    })->middleware('throttle:6,1')->name('verification.send');
    //https://www.dcodingsourcecode.darija-coding.com/tutorial/laravel-10-inertia-vue-3-authentication-with-email-verification-part-1
});


Route::middleware('guest')->group(function () {

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('user/login', [AuthController::class, 'auth'])->name('auth');

});
