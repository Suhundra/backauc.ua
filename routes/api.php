<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\GetAuction;
use App\Http\Controllers\BetSet;
use App\Http\Controllers\GetHistory;


Route::post('/register-form', [RegisterController::class, 'registerForm']);
Route::post('/login-form', [LoginController::class, 'loginForm']);

Route::post('/create-auc',[AuctionController::class, 'createAuc']);
Route::post('/update-auc',[AuctionController::class, 'updateAuction']);
Route::post('/delete-auc',[AuctionController::class, 'deleteAuction']);
Route::get('/get-auc',[GetAuction::class, 'getShortList']);
Route::get('/get-my-auc',[GetAuction::class, 'getMyAuctions']);
Route::get('/get-auc-by-id',[GetAuction::class, 'getAuctionById']);

Route::post('/set-bet',[BetSet::class, 'UpBet']);

Route::get('/get-history',[GetHistory::class, 'getAuctionHistory']);