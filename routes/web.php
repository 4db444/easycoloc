<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::prefix("/auth")->group(function () {
    Route::get("/login", [AuthController::class, "login"])->name("login");
    Route::get("/signup", [AuthController::class, "signup"]);
    Route::post("/signup", [AuthController::class, "store"]);
    Route::post("/login", [AuthController::class, "authenticate"]);
    Route::post("/logout", [AuthController::class, "logout"]);
});

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"]);

    Route::prefix("/groups")->group(function () {
        Route::get("/", [GroupController::class, "index"]);
        Route::post("/", [GroupController::class, "store"]);
        Route::get("/{id}", [GroupController::class, "show"]);
    });
});

Route::fallback(function () {
    return view("404");
});