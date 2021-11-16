<?php

use App\Http\Controllers\UserController;
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

Route::get('blog', [BlogController::class,'index']);
Route::post('blog', [BlogController::class,'store']);
Route::get('blog/{blog}', [BlogController::class,'show']);
Route::delete('blog/{blog}', [BlogController::class,'delete']);
Route::get('blog/likes/{blog}', [BlogController::class,'getLikeBlog']);
Route::get('blogs/check_out_blogs', [BlogController::class,'checkOutOtherBlog']);
Route::get('blogs/trending', [BlogController::class,'checkOutOtherBlog']);
Route::get('blog_settings/{blog}', [BlogSettingController::class,'show']);
Route::put('blog_settings/{blog}', [BlogSettingController::class,'update']);

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

Route::post('/login', [UserController::class,'login'])->name('login');

Route::post('/register', [UserController::class,'register'])->name('register');

Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:api');

Route::get('/email/verify/{id}/{hash}', [UserController::class,'emailVerification'])->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend_verification', [UserController::class,'resendVerification'])->middleware(['auth:api', 'throttle:10,1'])->name('verification.send');
