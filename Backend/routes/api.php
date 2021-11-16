<?php

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

Route::get('blog', [BlogController::class,'index'])->middleware('auth:api');
Route::post('blog', [BlogController::class,'store'])->middleware('auth:api');
Route::get('blog/{blog}', [BlogController::class,'show'])->middleware('auth:api');
Route::delete('blog/{blog}', [BlogController::class,'delete'])->middleware('auth:api');
Route::get('blog/likes/{blog}', [BlogController::class,'getLikeBlog']);
Route::get('blogs/check_out_blogs', [BlogController::class,'checkOutOtherBlog'])->middleware('auth:api');
Route::get('blogs/trending', [BlogController::class,'checkOutOtherBlog']);
Route::get('blog_settings/{blog}', [BlogSettingController::class,'show']);
Route::put('blog_settings/{blog}', [BlogSettingController::class,'update'])->middleware('auth:api');

Route::post('post/{blog_id}', [PostController::class,'store']);
Route::apiResource('/post', PostController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
