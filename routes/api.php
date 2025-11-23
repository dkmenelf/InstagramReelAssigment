<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramReelController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/instagram/reel/download', [InstagramReelController::class, 'downloadReel']);

Route::get('/instagram/reels', [InstagramReelController::class, 'listReels']);

