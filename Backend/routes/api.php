<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UploadFilesController;
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

Route::post('post/{blog_id}', [PostController::class,'store']);
Route::apiResource('/post', PostController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post(
    "/upload_photo/{blog_id}",
    [UploadFilesController::class, 'uploadPhoto']
)->where([ 'blog_id' => '[0-9]{9}' ]);

Route::post(
    "/upload_audio/{blog_id}",
    [UploadFilesController::class, 'uploadAudio']
)->where([ 'blog_id' => '[0-9]{9}' ]);

Route::post(
    "/upload_video/{blog_id}",
    [UploadFilesController::class, 'uploadVideo']
)->where([ 'blog_id' => '[0-9]{9}' ]);

Route::post(
    "/upload_ext_photo/{blog_id}",
    [UploadFilesController::class, 'uploadExtPhoto']
)->where([ 'blog_id' => '[0-9]{9}' ]);

Route::post(
    "/upload_ext_video/{blog_id}",
    [UploadFilesController::class, 'uploadExtVideo']
)->where([ 'blog_id' => '[0-9]{9}' ]);
