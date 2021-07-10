<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\SellerController;

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
// homepage
Route::get('/', function () {
    
    return redirect()->route('admin-dashboard');
});

// dashboard
Route::prefix('dashboard')
    ->middleware(['auth:sanctum','admin'])
    ->group(function(){
        Route::get('/',[DashboardController::class,'index'])->name('admin-dashboard');
        Route::resource('users', UserController::class);
        Route::resource('food', FoodController::class);

        Route::get('transactions/{id}/status/{status}',[TransactionController::class, 'changeStatus'])
        ->name('transactions.changeStatus');
        Route::get('transactions/export/excel',[TransactionController::class, 'exportExcel'])
        ->name('transactions.exportExcel');
        Route::resource('transactions', TransactionController::class);
  
        Route::get('orders/{id}/status/{status}',[OrderController::class, 'changeStatus'])
        ->name('orders.changeStatus');
        Route::resource('orders', OrderController::class);
        Route::resource('sellers', SellerController::class);
    });


// midtrans related
Route::get('midtrans/success', [MidtransController::class,'success']);
Route::get('midtrans/unfinish', [MidtransController::class,'unfinish']);
Route::get('midtrans/error', [MidtransController::class,'error']);
