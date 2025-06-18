<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use App\Http\Requests\EmailVerificationRequest;

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
//Route::get('/search',[ItemController::class,'index'])->name('search');
Route::get('/item/{item_id}',[ItemController::class,'show'])->name('item.show');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register',[RegisteredUserController::class,'store']);

//メール認証
Route::get('/email/verify', function(){
    return view('auth.verify-email');
})->name('verification.notice');

//メール認証リンクの再送
Route::post('/email/verification-notification', function(Request $request) {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    session()->put('resent',true);
    return back()->with('message', 'Verification link sent!');
})->name('verification.send');

//メール認証リンクで認証後、プロフィール設定画面へ遷移
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect()->route('profile.setup');
})->name('verification.verify');


//メール認証済みのユーザーのみアクセスOK
Route::middleware(['auth'])->group(function(){
    //いいね＆コメント機能
    Route::post('/item/{item_id}/like',[LikeController::class,'like'])->name('item.like');
    Route::post('/item/{item_id}/comment',[CommentController::class,'comment'])->name('item.comment');

    //マイページ
    Route::get('/mypage',[UserController::class,'index'])->name('mypage');
    Route::get('/mypage/profile',[UserController::class,'edit'])->name('profile.setup');
    Route::post('/mypage/profile',[UserController::class,'update']);

    //出品
    Route::get('/sell',[ItemController::class,'create']);
    Route::post('/sell',[ItemController::class,'store'])->name('sell');
});

//メール認証済みのユーザーのみアクセスOK
Route::middleware(['web', 'auth'])->group(function(){
    Route::get('/purchase/{item_id}',[OrderController::class,'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}',[OrderController::class,'updatePayment'])->name('purchase.updatePayment');
    Route::get('/purchase/address/{item_id}',[OrderController::class,'edit']);
    Route::post('/purchase/address/{item_id}',[OrderController::class,'update'])->name('purchase.updateAddress');
    Route::post('/purchase/order/{item_id}',[OrderController::class,'order'])->name('purchase.order');
});