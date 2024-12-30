<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/stocks');
    }
    return redirect('/auth/login');
});

Route::get("/auth/login", [LoginController::class, "login"]);
Route::post("/auth/login", [LoginController::class, "loginPost"]);
Route::get("/auth/register", [LoginController::class, "register"]);
Route::post("/auth/register", [LoginController::class, "registerPost"]);
Route::get("/auth/logout", [LoginController::class, "logout"]);

Route::get("/stocks", [StocksController::class, "getStockInfo"]);
Route::post("/api/stocks/{id}/buy", [StocksController::class, "buyStock"]);
Route::post("/api/stocks/{id}/sell", [StocksController::class, "sellStock"]);
Route::get("/api/stocks/news", [StocksController::class, "getStockNews"]);

Route::get("/wallet", [WalletController::class, "getWallet"]);
Route::get("/wallet/new", [WalletController::class, "newWallet"]);
Route::post("/wallet/new", [WalletController::class, "createWallet"]);
Route::get("/api/wallet/{id}/transactions", [WalletController::class, "getTransactions"]);
Route::post("/api/wallet/{id}/deposit", [WalletController::class, "deposit"]);
Route::post("/api/wallet/{id}/withdraw", [WalletController::class, "withdraw"]);

Route::get("/user", [UserController::class, "index"]);
Route::get("/user/addAddress", [UserController::class, "addAddress"]);
Route::post("/user/addAddress", [UserController::class, "postAddress"]);
Route::get("/api/user/deleteAddress", [UserController::class, "deleteAddress"]);
Route::get("/api/address/sehirler", [UserController::class, "ajaxSehirler"]);
Route::get("/api/address/ilceler", [UserController::class, "ajaxIlceler"]);
Route::get("/api/address/mahalleler", [UserController::class, "ajaxMahalleler"]);
