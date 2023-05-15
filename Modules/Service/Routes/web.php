<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\Admin\OrderController;
use Modules\Service\Http\Controllers\Admin\ServiceController;
use Modules\Service\Http\Controllers\Admin\ServiceItemController;

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
   // 'middleware' => ['auth'],
    'middleware' => ['role:Admins'],
], function () {

    Route::resource('/service', ServiceController::class);

    Route::get('/service-items/{service}', [ServiceItemController::class, 'index'])->name('service-items.index');
    Route::post('/service-items', [ServiceItemController::class, 'store'])->name('service-items.store');
    Route::patch('/service-items/{id}', [ServiceItemController::class, 'update'])->name('service-items.update');
    Route::post('/service-items/position', [ServiceItemController::class, 'position'])->name('service-items.position');

    Route::resource('/orders', OrderController::class);

});
