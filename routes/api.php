<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\Oembed\TwitterController;
use App\Http\Controllers\Api\Oembed\YoutubeController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 上傳圖片至 S3
Route::middleware('auth:sanctum')
    ->post('/images/upload', [ImageController::class, 'store'])
    ->name('images.store');

Route::get('tags', TagController::class)->name('api.tags');

Route::post('oembed/twitter', TwitterController::class);
Route::post('oembed/youtube', YoutubeController::class);
