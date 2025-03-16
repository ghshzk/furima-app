<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

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

Route::get('/',[ItemController::class,'index'])->name('top');
Route::get('/item/search',[ItemController::class,'search'])->name('item.search');
Route::get('/item/{item_id}',[ItemController::class,'show'])->name('item.show');

Route::middleware(['auth'])->group(function(){
    Route::post('/item/{item_id}/like',[LikeController::class,'like'])->name('item.like');
    Route::post('/item/{item_id}/comment',[CommentController::class,'comment'])->name('item.comment');

    Route::get('/mypage',[UserController::class,'index'])->name('mypage');
    Route::get('/mypage/profile',[UserController::class,'edit']);
    Route::post('/mypage/profile',[UserController::class,'update']);

    Route::get('/sell',[ItemController::class,'create']);
    Route::post('/sell',[ItemController::class,'store'])->name('sell');
});

Route::middleware(['web', 'auth'])->group(function(){
    Route::get('/purchase/{item_id}',[OrderController::class,'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}',[OrderController::class,'updatePayment'])->name('purchase.updatePayment');
    Route::get('/purchase/address/{item_id}',[OrderController::class,'edit']);
    Route::post('/purchase/address/{item_id}',[OrderController::class,'update'])->name('purchase.updateAddress');
    Route::post('/purchase/order/{item_id}',[OrderController::class,'order'])->name('purchase.order');
});