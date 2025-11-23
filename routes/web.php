<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramReelController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/instagram/reel/download', [InstagramReelController::class, 'downloadReel']);

Route::get('/instagram/reels', [InstagramReelController::class, 'listReels']);
