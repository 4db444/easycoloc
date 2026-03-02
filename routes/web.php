<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettlementController;
use Illuminate\Support\Facades\Route;

Route::prefix("/auth")->group(function () {
    Route::get("/login", [AuthController::class, "login"])->name("login");
    Route::get("/signup", [AuthController::class, "signup"]);
    Route::post("/signup", [AuthController::class, "store"]);
    Route::post("/login", [AuthController::class, "authenticate"]);
    Route::post("/logout", [AuthController::class, "logout"]);
});

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"]);

    // Profile routes
    Route::get("/profile", [ProfileController::class, "show"]);
    Route::put("/profile", [ProfileController::class, "update"]);
    Route::put("/profile/password", [ProfileController::class, "changePassword"]);
    Route::get("/users/{user}", [ProfileController::class, "showPublic"]);

    Route::prefix("/groups")->group(function () {
        Route::get("/", [GroupController::class, "index"]);
        Route::post("/", [GroupController::class, "store"]);
        Route::get("/{group}", [GroupController::class, "show"]);
        Route::post("/{group}/categories", [CategoryController::class, "store"]);
        Route::delete("/{group}/categories/{category}", [CategoryController::class, "destroy"]);
        Route::post("/{group}/invite", [GroupController::class, "invite"]);
        Route::get("/{group}/join/{token}", [GroupController::class, "join_page"]);
        Route::post("/{group}/join", [GroupController::class, "join"]);
        Route::post("/{group}/expenses", [ExpenseController::class, "store"]);
        Route::delete("/{group}/expenses/{expense}", [ExpenseController::class, "destroy"]);
        Route::post("/{group}/settlements", [SettlementController::class, "store"]);
        Route::post("/{group}/leave", [GroupController::class, "leave"]);
        Route::post("/{group}/members/{member}/fire", [GroupController::class, "fire"]);
    });

    // Admin routes (admin middleware)
    Route::middleware("admin")->prefix("/admin")->group(function () {
        Route::get("/", [AdminController::class, "index"]);
        Route::post("/users/{targetUser}/ban", [AdminController::class, "ban"]);
        Route::post("/users/{targetUser}/unban", [AdminController::class, "unban"]);
    });
});

Route::fallback(function () {
    return view("404");
});