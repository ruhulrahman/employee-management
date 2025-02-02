<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('leave-request')->group(function () {
        Route::post('/add', [LeaveRequestController::class, 'createData']);
        Route::post('/update', [LeaveRequestController::class, 'updateData']);
        Route::get('/getAllPendingRequest', [LeaveRequestController::class, 'getAllPendingRequest']);
        Route::post('/ApproveLeaveRequest', [LeaveRequestController::class, 'ApproveLeaveRequest']);
        Route::get('/getAllRejectedRequest', [LeaveRequestController::class, 'getAllRejectedRequest']);
        Route::get('/getLoggedInUserLeaveRequest', [LeaveRequestController::class, 'getLoggedInUserLeaveRequest']);
        Route::get('/getAllLeaveRequest', [LeaveRequestController::class, 'getAllLeaveRequest']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/create', [UserController::class, 'createUser']);
        Route::post('/update', [UserController::class, 'updateUser']);
    });

});
