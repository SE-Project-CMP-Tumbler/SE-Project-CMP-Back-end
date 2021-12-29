<?php

use App\Http\Controllers\BlockBlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\BlogSettingController;
use App\Http\Controllers\FollowBlogController;
use App\Http\Controllers\FollowTagController;
use App\Http\Controllers\PostFilterController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostActionController;
use App\Http\Controllers\PostNoteController;
use App\Http\Controllers\AskController;
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
| Search Routes
*/
Route::get('search/{blog_id}/{word}', [SearchController::class,'searchBlog'])->middleware('auth:api');
Route::get('search/{word}', [SearchController::class,'search']);
Route::get('search_auto_complete/{word}', [SearchController::class,'recommendedWord']);
/*
| Theme Routes
 */
Route::get('/blog/{blog_id}/theme', [ThemeController::class,'show'])->middleware('auth:api');
Route::put('/blog/{blog_id}/theme', [ThemeController::class,'update'])->middleware('auth:api');

/*
| Blog Routes
*/
Route::get('blog', [BlogController::class,'index'])->middleware('auth:api');
Route::post('blog', [BlogController::class,'store'])->middleware('auth:api');
Route::get('blog/{blog_id}', [BlogController::class,'show'])->middleware('auth:api');
Route::delete('blog/{blog_id}', [BlogController::class,'delete'])->middleware('auth:api');
Route::get('blogs/likes/{blog_id}', [BlogController::class,'getLikeBlog'])->middleware('auth:api');
Route::get('blogs/check_out_blogs', [BlogController::class,'checkOutOtherBlog'])->middleware('auth:api');
Route::get('blogs/trending', [BlogController::class,'getTrendingBlog'])->middleware('auth:api');
Route::get('blog_settings/{blog_id}', [BlogSettingController::class,'show'])->middleware('auth:api');
Route::put('blog_settings/{blog_id}', [BlogSettingController::class,'update'])->middleware('auth:api');
Route::get('blog_activity/{blog_id}', [ActivityController::class,'getActivityBlog'])->middleware('auth:api');
Route::get('blog/info/{blog_username}', [BlogController::class, 'getBlogByUsername'])->middleware('auth:api');

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
Route::get('followings/{blog_id}', [FollowBlogController::class,'getanotherFollowings'])->middleware('auth:api');
/*
| Block Blog Routes
*/
Route::post('block/{blockerId}/{blockedId}', [BlockBlogController::class, 'block'])->middleware('auth:api');
Route::delete('block/{blockerId}/{blockedId}', [BlockBlogController::class, 'unblock'])->middleware('auth:api');
Route::get('block/{blockerId}/{blockedId}', [BlockBlogController::class, 'checkIsBlocking'])->middleware('auth:api');
Route::get('block/{blogId}', [BlockBlogController::class, 'getBlockings'])->middleware('auth:api');

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
| Submissions Routes
*/
Route::post('post/submission/{blog_id}', [PostController::class, 'createSubmission'])->middleware('auth:api');
Route::get('post/submission/{blog_id}', [PostController::class, 'getSubmissions'])->middleware('auth:api');
Route::post('post/approve/{post_id}', [PostController::class, 'approveSubmission'])->middleware('auth:api');
Route::delete('post/submission/{post_id}', [PostController::class, 'delete'])->middleware('auth:api');

Route::delete('posts/submission', [PostController::class, 'deleteAllSubmissions'])->middleware('auth:api');

/*
| Reblog Route
*/
Route::post('/reblog/{blog_id}/{parent_post_id}', [PostController::class, 'createReblog'])->middleware('auth:api');

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
| Chat Routes
*/
Route::post("/chat/messages/{chat_room_id}", [ChatController::class, 'getAllMessages'])->middleware('auth:api');
Route::post("/chat/new_message/{chat_room_id}", [ChatController::class, 'sendMessage'])->middleware('auth:api');
Route::delete("/chat/clear_chat/{chat_room_id}", [ChatController::class, 'clearChat'])->middleware('auth:api');

Route::post("/chat/chat_search", [ChatController::class, 'chatSearch'])->middleware('auth:api');
Route::post("/chat/chat_id", [ChatController::class, 'getChatRoomGID'])->middleware('auth:api');
Route::post("/chat/all_chats", [ChatController::class, 'getLastMessages'])->middleware('auth:api');

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
Route::put('/change_email', [UserController::class,'changeEmail'])->name('email.change')->middleware(['auth:api']);
Route::delete('/delete_user', [UserController::class,'deleteUser'])->name('user.delete')->middleware(['auth:api']);

/*
| Tags Routes
*/
Route::post('/tag/data/{post_id}/{tag_description}', [TagController::class,'store'])->middleware('auth:api');
Route::get('/tag/data/{tag_description}', [TagController::class,'show'])->middleware('auth:api');
Route::get('/tag/is_following/{tag_description}', [TagController::class,'checkIsFollowing'])->middleware('auth:api');
Route::get('/tag/trending', [TagController::class,'index']);
Route::get('/tag/suggesting', [TagController::class,'getSuggestions'])->middleware('auth:api');
Route::get('/tag/posts/{tag_description}', [TagController::class, 'getTagPosts']);

/*
| Follow Tag Routes
*/
Route::post('/follow_tag/{tag_description}', [FollowTagController::class,'store'])->middleware('auth:api');
Route::delete('/follow_tag/{tag_description}', [FollowTagController::class,'destroy'])->middleware('auth:api');

Route::get('/follow_tag', [FollowTagController::class, 'getTagsFollowed'])->middleware('auth:api');

/*
| Post Notes Routes
*/
Route::post('/post/reply/{post_id}', [PostActionController::class,'addReply'])->middleware('auth:api');
Route::post('/post/like/{post_id}', [PostActionController::class,'addLike'])->middleware('auth:api');
Route::get('/post/like/{blog_id}/{post_id}', [PostActionController::class,'checkLiked'])->middleware('auth:api');
Route::delete('/post/like/{post_id}', [PostActionController::class,'deleteLike'])->middleware('auth:api');
Route::delete('/post/reply/{reply_id}', [PostActionController::class,'deletereply'])->middleware('auth:api');
Route::get('/post_notes/{post_id}', [PostNoteController::class,'getNotes'])->middleware('auth:api');

/*
| Asks Routes
*/
Route::post('/ask/{blog_id}', [AskController::class,'ask'])->middleware('auth:api');
Route::post('/answer/{question_id}', [AskController::class,'answer'])->middleware('auth:api');
Route::delete('/ask/{question_id}', [AskController::class,'deleteAsk'])->middleware('auth:api');
Route::get('/messages/{blog_id}', [AskController::class,'getMessagesForBlog'])->middleware('auth:api');
Route::get('/all_messages', [AskController::class,'getMessages'])->middleware('auth:api');
Route::delete('/messages/{blog_id}', [AskController::class,'deleteMessagesForBlog'])->middleware('auth:api');
Route::delete('/all_messages', [AskController::class,'deleteMessages'])->middleware('auth:api');

/*
| Notifications Routes
*/
Route::get('notifications', [NotificationController::class, 'notificaions'])->middleware('auth:api')->middleware('notification_read');

/*
| Graph Routes
*/
Route::get('graph/notes/{blog_id}', [ActivityController::class, 'getNotesGraphData'])->middleware('auth:api');
Route::get('graph/new_followers/{blog_id}', [ActivityController::class, 'getNewFollwersGraphData'])->middleware('auth:api');
Route::get('graph/total_followers/{blog_id}', [ActivityController::class, 'getTotalFollwersGraphData'])->middleware('auth:api');
