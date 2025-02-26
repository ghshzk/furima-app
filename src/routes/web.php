<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

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

Route::get('/',[ItemController::class,'index'])->name('top');;


Route::get('/mypage',[UserController::class,'index']);

Route::middleware(['auth'])->group(function(){
    Route::get('/mypage/profile',[UserController::class,'edit']);
    Route::post('/mypage/profile',[UserController::class,'update']);
});

Route::middleware(['auth'])->group(function(){
    Route::get('/purchase/{item_id}',[OrderController::class,'index']);
    Route::get('/purchase/address/{item_id}',[OrderController::class,'edit']);
    Route::post('/purchase/address/{item_id}',[OrderController::class,'update']);
    Route::post('/purchase/{item_id}',[OrderController::class,'updatePayment']);
});
