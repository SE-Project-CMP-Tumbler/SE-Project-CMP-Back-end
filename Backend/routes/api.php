<?php

use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogSettingController;
use App\Http\Controllers\FollowBlogController;
use App\Http\Controllers\FollowTagController;
use App\Http\Controllers\PostFilterController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\Unit\PostFillterTest;

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
Route::get('blog/{blog_id}', [BlogController::class,'show'])->middleware('auth:api');
Route::delete('blog/{blog_id}', [BlogController::class,'delete'])->middleware('auth:api');
Route::get('blogs/likes/{blog_id}', [BlogController::class,'getLikeBlog'])->middleware('auth:api');
Route::get('blogs/check_out_blogs', [BlogController::class,'checkOutOtherBlog'])->middleware('auth:api');
Route::get('blog_settings/{blog_id}', [BlogSettingController::class,'show'])->middleware('auth:api');
Route::put('blog_settings/{blog_id}', [BlogSettingController::class,'update'])->middleware('auth:api');
/*
| Follow Blog Routes
*/
Route::post('follow_blog/{blog_id}', [FollowBlogController::class,'store'])->middleware('auth:api');
Route::delete('follow_blog/{blog_id}', [FollowBlogController::class,'delete'])->middleware('auth:api');
Route::get('followed_by/{blog_id}', [FollowBlogController::class,'checkFollowed'])->middleware('auth:api');
/*
| Post Routes
*/
Route::get('post/{postId}', [PostController::class,'show']);
Route::post('post/{blog_id}', [PostController::class,'store'])->middleware('auth:api');
Route::delete('post/{postId}', [PostController::class,'delete'])->middleware('auth:api');
Route::put('post/{postId}', [PostController::class,'update'])->middleware('auth:api');

Route::get('/posts/random_posts', [PostFilterController::class, 'getRandomPosts']);
Route::get('/posts/trending', [PostFilterController::class, 'getTrendingPosts']);
Route::get('/posts/{blogId}/published', [PostController::class, 'index']);
Route::get('/post/{blogId}/draft', [PostController::class, 'getDraftPosts'])->middleware('auth:api');
Route::get('/posts/dashboard', [PostFilterController::class, 'getDashboardPosts'])->middleware('auth:api');

Route::get('/posts/text', [PostFilterController::class, 'getTextPosts']);
Route::get('/posts/quote', [PostFilterController::class, 'getQuotePosts']);
Route::get('/posts/video', [PostFilterController::class, 'getVideoPosts']);
Route::get('/posts/audio', [PostFilterController::class, 'getAudioPosts']);
/*
| Uploads Routes
*/
Route::post("/upload_photo", [UploadFileController::class, 'uploadImage'])->middleware('auth:api');
Route::post("/upload_audio", [UploadFileController::class, 'uploadAudio'])->middleware('auth:api');
Route::post("/upload_video", [UploadFileController::class, 'uploadVideo'])->middleware('auth:api');
Route::post("/upload_ext_photo", [UploadFileController::class, 'uploadExtImage'])->middleware('auth:api');
Route::post("/upload_ext_video", [UploadFileController::class, 'uploadExtVideo'])->middleware('auth:api');

/*
| User Routes
*/
Route::post('/login', [UserController::class,'login'])->name('login');
Route::post('/register', [UserController::class,'register'])->name('register');
Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:api');
Route::get('/email/verify/{id}/{hash}', [UserController::class,'emailVerification'])->name('verification.verify');
Route::post('/email/resend_verification', [UserController::class,'resendVerification'])->middleware(['auth:api', 'throttle:10,1'])->name('verification.send');


/*
| Tags Routes
*/
Route::post('/tag/data/{post_id}/{tag_description}', [TagController::class,'store'])->middleware('auth:api');
Route::get('/tag/data/{tag_description}', [TagController::class,'show']);
Route::get('/tag/trending', [TagController::class,'index']);

/*
| Follow Tag Routes
*/
Route::post('/follow_tag/{tag_description}', [FollowTagController::class,'store'])->middleware('auth:api');
Route::delete('/follow_tag/{tag_description}', [FollowTagController::class,'destroy'])->middleware('auth:api');
