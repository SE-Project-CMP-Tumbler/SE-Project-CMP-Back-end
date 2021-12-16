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
Route::get('total_followers/{blog_id}', [FollowBlogController::class,'getTotalFollowers'])->middleware('auth:api');
Route::get('total_followings/{blog_id}', [FollowBlogController::class,'getTotalFollowings'])->middleware('auth:api');
Route::get('followings', [FollowBlogController::class,'getFollowings'])->middleware('auth:api');
Route::get('followers', [FollowBlogController::class,'getFollowers'])->middleware('auth:api');
Route::get('search_follow_blog/{blog_username}', [FollowBlogController::class,'searchFollowBlog'])->middleware('auth:api');
Route::post('follow_blog_search', [FollowBlogController::class,'followBlog'])->middleware('auth:api');

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

Route::get('posts/chat', [PostFilterController::class, 'getChatPosts']);
Route::get('posts/image', [PostFilterController::class, 'getImagePosts']);
Route::get('posts/ask', [PostFilterController::class, 'getAskPosts']);
Route::get('posts/radar', [PostFilterController::class, 'getRadarPost']);

Route::put('posts/pin', [PostController::class, 'pinPost'])->middleware('auth:api');
Route::put('posts/unpin', [PostController::class, 'unpinPost'])->middleware('auth:api');
Route::put('posts/change_status', [PostController::class, 'changePostStatus'])->middleware('auth:api');

/*
| Uploads Routes
*/
Route::post("/upload_photo", [UploadFileController::class, 'uploadImage'])->middleware('auth:api');
Route::post("/upload_audio", [UploadFileController::class, 'uploadAudio'])->middleware('auth:api');
Route::post("/upload_video", [UploadFileController::class, 'uploadVideo'])->middleware('auth:api');
Route::post("/upload_ext_photo", [UploadFileController::class, 'uploadExtImage'])->middleware('auth:api');
Route::post("/upload_ext_video", [UploadFileController::class, 'uploadExtVideo'])->middleware('auth:api');

Route::post("/upload_base64_photo", [UploadFileController::class, 'uploadBase64Image'])->middleware('auth:api');
Route::post("/upload_base64_audio", [UploadFileController::class, 'uploadBase64Audio'])->middleware('auth:api');
Route::post("/upload_base64_video", [UploadFileController::class, 'uploadBase64Video'])->middleware('auth:api');


/*
| User Routes
*/
Route::post('/login', [UserController::class,'login'])->name('login');
Route::post('/register', [UserController::class,'register'])->name('register');
Route::post('/check_register_credentials', [UserController::class,'checkRegisterCredentials'])->name('register.check');
Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:api');
Route::get('/email/verify/{id}/{hash}', [UserController::class,'emailVerification'])->name('verification.verify');
Route::post('/email/resend_verification', [UserController::class,'resendVerification'])->middleware(['auth:api', 'throttle:10,1'])->name('verification.send');
Route::post('/forgot_password', [UserController::class,'forgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset_password/{id}/{token}', [UserController::class,'resetPasswordLink'])->middleware('guest')->name('password.update');
Route::post('/reset_password', [UserController::class,'resetPassword'])->middleware('guest')->name('password.update');
Route::post('/register_with_google', [UserController::class,'registerWithGoogle'])->name('register.google');
Route::post('/login_with_google', [UserController::class,'loginWithGoogle'])->name('login.google');
Route::put('/change_password', [UserController::class,'changePassword'])->name('password.change')->middleware(['auth:api']);

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
