<?php

use App\Http\Controllers\Api\LocationWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/location-webhook', [LocationWebhookController::class, 'store']);
