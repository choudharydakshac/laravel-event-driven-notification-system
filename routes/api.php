<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('v1')->group(function () {

    Route::post('/users/register', [NotificationController::class, 'registerUser']);

    Route::get('/notifications', [NotificationController::class, 'index']);
});