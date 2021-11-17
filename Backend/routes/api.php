<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogSettingController;
use App\Http\Controllers\TagController;
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

/*
| Blog Routes
*/
Route::get('blog', [BlogController::class,'index'])->middleware('auth:api');
Route::post('blog', [BlogController::class,'store'])->middleware('auth:api');
Route::get('blog/{blog}', [BlogController::class,'show'])->middleware('auth:api');
Route::delete('blog/{blog}', [BlogController::class,'delete'])->middleware('auth:api');
Route::get('blog/likes/{blog}', [BlogController::class,'getLikeBlog']);
Route::get('blogs/check_out_blogs', [BlogController::class,'checkOutOtherBlog'])->middleware('auth:api');
Route::get('blogs/trending', [BlogController::class,'checkOutOtherBlog']);
Route::get('blog_settings/{blog}', [BlogSettingController::class,'show']);
Route::put('blog_settings/{blog}', [BlogSettingController::class,'update'])->middleware('auth:api');

/*
| Post Routes
*/
Route::post('post/{blog_id}', [PostController::class,'store'])
->where(['blog_id' => '[0-9]+']);
Route::delete('post/{post_id}/{blog_id}', [PostController::class,'destroy'])
->where(['blog_id' => '[0-9]+',
        'post_id' => '[0-9]+']);
Route::apiResource('/post', PostController::class);

/*
| Uploads Routes
*/
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

/*
| User Routes
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class,'login'])->name('login');

Route::post('/register', [UserController::class,'register'])->name('register');

Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:api');

Route::get('/email/verify/{id}/{hash}', [UserController::class,'emailVerification'])->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend_verification', [UserController::class,'resendVerification'])->middleware(['auth:api', 'throttle:10,1'])->name('verification.send');

/*
| Tags Routes
*/
Route::post('/tag/data/{post_id}/{tag_description}', [TagController::class,'store']);
Route::get('/tag/data/{tag_description}', [TagController::class,'show']);
Route::get('/tag/trending', [TagController::class,'index']);
