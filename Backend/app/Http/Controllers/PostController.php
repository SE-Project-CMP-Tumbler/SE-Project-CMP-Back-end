<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    

/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}",
 * summary="Edit a new post",
 * description="A blog can edit post",
 * operationId="editpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *    in text type :description or title are required, at least one of them ,keep reading is optinal
 *    in image type : at least one uplaoded image,
 *    in chat type : chat_body is required ,chat_title is optional
 *    in quote type:  quote_text is required, quote_body is optinal
 *    in video type:  video is required , url_videos are optinal
 *    in audio type: audio is required
 *    in link type:  link is required
 *    is genral : all fields can be given , to be genarl at least two different field of types should given" ,
 *    @OA\JsonContent(
 *      required={"post_status","post_type"},
 *      @OA\Property(property="post_status", type="string", example="published"),
 *      @OA\Property(property="title", type="string", example="New post"),
 *      @OA\Property(property="description", type="string", example="new post"),
 *      @OA\Property(property="chat_title", type="string", example="New post"),
 *      @OA\Property(property="chat_body", type="string", example="My post"),
 *      @OA\Property(property="quote_text", type="string", example="New post"),
 *      @OA\Property(property="quote_resouce", type="string", example="My post"),
 *      @OA\Property(property="keep_reading", type="integer", example=1),
 *      @OA\Property(property="link",type="string",example="facebook.com"),
 *      @OA\Property(property="scheulding_time",type="date-time",example=""),
 *      @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *      @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", format="byte",example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *              @OA\Property(property="1", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *              @OA\Property(property="2", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                  )
 *           ),
 *      @OA\Property(property="video ", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *      @OA\Property(property="audio ", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *      @OA\Property(property="post_type ", type="string", example="text"),
 *      @OA\Property(property="url_videos ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="facebook.com"),
 *              @OA\Property(property="1", type="string", example="google.com"),
 *              @OA\Property(property="2", type="string", example="yahoo.com"),)))),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="post_id", type="integer", example=5),
 *       @OA\Property(property="pinned", type="boolean", example=false),
 *       @OA\Property(property="blog_id", type="integer", example=5),
 *       @OA\Property(property="blog_username", type="string", example=""),
 *       @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *       @OA\Property(property="blog_title", type="string", example=""),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="question_body", type="string", example="How are you?"),
 *       @OA\Property(property="question_id", type="integer", example=3),
 *       @OA\Property(property="question_flag", type="boolean", example=false),
 *       @OA\Property(property="blog_id_asking", type="integer", example=""),
 *       @OA\Property(property="blog_username_asking", type="string", example=""),
 *       @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *        @OA\Property(property="blog_title_asking", type="string", example=""),
 *       @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string",example="/image.png"),
 *              @OA\Property(property="1", type="string", example="/image.png"),
 *              @OA\Property(property="2", type="string",example="/image.png"),
 *                  )
 *           ),
 *       @OA\Property(property="video ", type="string", example="/audio.mp3"),
 *       @OA\Property(property="audio ", type="string", example="/video.mov"),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="facebook.com"),
 *              @OA\Property(property="1", type="string", example="google.com"),
 *              @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *       @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *              @OA\Property(property="post_type", type="string", example="text"),
 *              @OA\Property(property="post_body", type="string", example="<div><h1>The title</h1> <p>a post of type text.</p></div>"),))),),),
 *@OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Delete(
 * path="/post/{post_id}/{blog_id}",
 * summary="Delete post",
 * description=" A blog delete his/her post",
 * operationId="deletepost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Get(
 * path="/post/{post_id}",
 * summary="Get specific post",
 * description=" A blog get  post",
 * operationId="getapost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *   @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="post_status", type="string", example="published"),
 *      @OA\Property(property="post_id", type="integer", example=5),
 *      @OA\Property(property="blog_id", type="integer", example=5),
 *      @OA\Property(property="blog_username", type="string", example=""),
 *      @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *      @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *      @OA\Property(property="blog_title", type="string", example=""),
 *      @OA\Property(property="pinned", type="boolean", example=false),
 *      @OA\Property(property="title", type="string", example="New post"),
 *      @OA\Property(property="description", type="string", example="new post"),
 *      @OA\Property(property="chat_title", type="string", example="New post"),
 *      @OA\Property(property="chat_body", type="string", example="My post"),
 *      @OA\Property(property="quote_text", type="string", example="New post"),
 *      @OA\Property(property="quote_resouce", type="string", example="My post"),
 *      @OA\Property(property="link",type="string",example="facebook.com"),
 *      @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *      @OA\Property(property="keep_reading", type="integer", example=1),
 *      @OA\Property(property="scheulding_time",type="date-time",example=""),
 *      @OA\Property(property="blog_username_asking", type="string", example=""),
 *      @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *      @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *      @OA\Property(property="blog_title_asking", type="string", example=""),
 *      @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *      @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", format="byte",example="./image.png"),
 *              @OA\Property(property="1", type="string", format="byte", example="./image.png"),
 *              @OA\Property(property="2", type="string", format="byte", example="./image.png"),
 *                  )
 *           ),
 *      @OA\Property(property="video ", type="string", format="byte", example="./videp.mov"),
 *      @OA\Property(property="audio ", type="string", format="byte", example="./audio.mp3"),
 *      @OA\Property(property="post_type ", type="string", example="text"),
 *      @OA\Property(property="url_videos ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="facebook.com"),
 *              @OA\Property(property="1", type="string", example="google.com"),
 *              @OA\Property(property="2", type="string", example="yahoo.com"),),
 *      ),
 *      @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),))
 *     ),
 * )
 *   ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

 /**
 * @OA\Post(
 * path="/post/{blog_id}",
 * summary="create new post",
 * description="A blog can create new post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *     in text type :description or title are required, at least one of them ,keep reading is optinal
 *     in image type : at least one uplaoded image,
 *     in chat type : chat_body is required, chat_title is optional
 *     in quote type:  quote_text is required, quote_body is optinal
 *     in video type:  video is required, url_videos are optinal
 *     in audio type: audio is required
 *     is general : all fields can be given, to be general at least two different field of types should given",
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="chat_title", type="string", example="New post"),
 *       @OA\Property(property="chat_body", type="string", example="My post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="link",type="string",example="facebook.com"),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="scheulding_time",type="date-time",example=""),
 *       @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *      @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                      @OA\Property(property="1", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                      @OA\Property(property="2", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                  )
 *           ),
 *       @OA\Property(property="video ", type="string", format="byte", example=""),
 *       @OA\Property(property="audio ", type="string", format="byte", example=""),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                  )
 *       )
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *       @OA\Property(property="post_id", type="integer", example=5),
 *       @OA\Property(property="blog_id", type="integer", example=5),
 *       @OA\Property(property="blog_username", type="string", example=""),
 *       @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *       @OA\Property(property="blog_title", type="string", example=""),
 *       @OA\Property(property="pinned", type="boolean", example=false),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="chat_title", type="string", example="New post"),
 *       @OA\Property(property="chat_body", type="string", example="My post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="link",type="string",example="facebook.com"),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="scheulding_time",type="date-time",example=""),
 *       @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *              @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *              @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *       @OA\Property(property="video ", type="string", format="byte", example=""),
 *       @OA\Property(property="audio ", type="string", format="byte", example=""),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"))),
 *       @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),))
 *        ),
 *      ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *     )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *     )
 *  ),
 * )
 */

/**
 * @OA\Get(
 * path="/post/{blog_id}/submission",
 * summary="Get posts of blog which are submitted",
 * description=" A blog get submitted posts",
 * operationId="getsubmissionposts",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_status", type="string", example="submission"),
 *              @OA\Property(property="post_tags", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", example="books"),
 *                      @OA\Property(property="1", type="string", example="reading"),
 *                      @OA\Property(property="2", type="string", example="stay positive"),)),
 *              @OA\Property(property="title", type="string", example="New post"),
 *              @OA\Property(property="description", type="string", example="new post"),
 *              @OA\Property(property="chat_title", type="string", example="New post"),
 *              @OA\Property(property="chat_body", type="string", example="My post"),
 *              @OA\Property(property="quote_text", type="string", example="New post"),
 *              @OA\Property(property="quote_resouce", type="string", example="My post"),
 *              @OA\Property(property="link",type="string",example="facebook.com"),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *              @OA\Property(property="keep_reading", type="integer", example=1),
 *              @OA\Property(property="images ", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *              @OA\Property(property="video ", type="string", format="byte", example="./video.mov"),
 *              @OA\Property(property="audio ", type="string", format="byte", example="./audio.mp3"),
 *              @OA\Property(property="post_type ", type="string", example="text"),
 *              @OA\Property(property="url_videos ", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *              @OA\Property(property="traced_back_posts", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="post_id", type="integer", example=5),
 *                      @OA\Property(property="blog_id", type="integer", example=5),
 *                      @OA\Property(property="title", type="string", example="New post"),
 *                      @OA\Property(property="description", type="string", example="new post"),
 *                      @OA\Property(property="chat_title", type="string", example="New post"),
 *                      @OA\Property(property="chat_body", type="string", example="My post"),
 *                      @OA\Property(property="post_tags", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", example="books"),
 *                              @OA\Property(property="1", type="string", example="reading"),
 *                              @OA\Property(property="2", type="string", example="stay positive"),)),
 *                      @OA\Property(property="quote_text", type="string", example="New post"),
 *                      @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                      @OA\Property(property="keep_reading", type="integer", example=1),
 *                      @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                              @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                              @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *                      @OA\Property(property="video ", type="string", format="byte", example=""),
 *                      @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                      @OA\Property(property="post_type ", type="string", example="text"),
 *                      @OA\Property(property="url_videos ", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", example="facebook.com"),
 *                              @OA\Property(property="1", type="string", example="google.com"),
 *                              @OA\Property(property="2", type="string", example="yahoo.com"),),),))
 *           ),
 *        ),
 *       ),
 *     ),
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Get(
 * path="/post/{blog_id}/queue",
 * summary="Get posts of blog which are queued ",
 * description=" A blog get queued posts",
 * operationId="getqueuepost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *       @OA\Property(property="post_status", type="string", example="queue"),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="chat_title", type="string", example="New post"),
 *       @OA\Property(property="chat_body", type="string", example="My post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="link",type="string",example="facebook.com"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="images ", type="array",
 *        @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *       @OA\Property(property="video ", type="string", format="byte", example=""),
 *       @OA\Property(property="audio ", type="string", format="byte", example=""),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *       @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="title", type="string", example="New post"),
 *              @OA\Property(property="description", type="string", example="new post"),
 *              @OA\Property(property="chat_title", type="string", example="New post"),
 *              @OA\Property(property="chat_body", type="string", example="My post"),
 *              @OA\Property(property="post_tags", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", example="books"),
 *                      @OA\Property(property="1", type="string", example="reading"),
 *                      @OA\Property(property="2", type="string", example="stay positive"),)),
 *              @OA\Property(property="quote_text", type="string", example="New post"),
 *              @OA\Property(property="quote_resouce", type="string", example="My post"),
 *              @OA\Property(property="keep_reading", type="integer", example=1),
 *              @OA\Property(property="images ", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *              @OA\Property(property="video ", type="string", format="byte", example=""),
 *              @OA\Property(property="audio ", type="string", format="byte", example=""),
 *              @OA\Property(property="post_type ", type="string", example="text"),
 *              @OA\Property(property="url_videos ", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),),),))
 *        ),
 *       ),
 *      ),
 *     ),
 * )
 *   ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Get(
 * path="/post/{blog_id}/scheduling ",
 * summary="Get posts of blog which are scheduled  ",
 * description=" A blog get scheduled posts",
 * operationId="getschedulingpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *          @OA\Property(property="post_status", type="string", example="scheduling"),
 *          @OA\Property(property="title", type="string", example="New post"),
 *          @OA\Property(property="scheulding_time",type="date-time",example="02-02-2021"),
 *          @OA\Property(property="description", type="string", example="new post"),
 *          @OA\Property(property="chat_title", type="string", example="New post"),
 *          @OA\Property(property="chat_body", type="string", example="My post"),
 *          @OA\Property(property="quote_text", type="string", example="New post"),
 *          @OA\Property(property="quote_resouce", type="string", example="My post"),
 *          @OA\Property(property="keep_reading", type="integer", example=1),
 *          @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          @OA\Property(property="link",type="string",example="facebook.com"),
 *          @OA\Property(property="images ", type="array",
 *              @OA\Items(
 *                  @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                  @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                  @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *          @OA\Property(property="video ", type="string", format="byte", example=""),
 *          @OA\Property(property="audio ", type="string", format="byte", example=""),
 *          @OA\Property(property="post_type ", type="string", example="text"),
 *          @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *              @OA\Property(property="0", type="string", example="facebook.com"),
 *              @OA\Property(property="1", type="string", example="google.com"),
 *              @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *          @OA\Property(property="traced_back_posts", type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="post_tags", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="books"),
 *                          @OA\Property(property="1", type="string", example="reading"),
 *                          @OA\Property(property="2", type="string", example="stay positive"),)),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),),))
 *          ),
 *        ),
 *       ),
 *     ),
 * )
 *   ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
 
/**
 * @OA\Get(
 * path="/post/{blog_id}",
 * summary="Get posts of blog which are published",
 * description=" A blog get  blog 's posts",
 * operationId="getposts",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="blog_username", type="string", example=""),
 *                  @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                  @OA\Property(property="blog_title", type="string", example=""),
 *                  @OA\Property(property="pinned", type="boolean", example=false),
 *                  @OA\Property(property="post_tags", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="books"),
 *                          @OA\Property(property="1", type="string", example="reading"),
 *                          @OA\Property(property="2", type="string", example="stay positive"),)),
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *                  @OA\Property(property="link",type="string",example="facebook.com"),
 *                  @OA\Property(property="blog_username_asking", type="string", example=""),
 *                  @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *                  @OA\Property(property="blog_title_asking", type="string", example=""),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *                  @OA\Property(property="traced_back_posts", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="post_id", type="integer", example=5),
 *                      @OA\Property(property="blog_id", type="integer", example=5),
 *                      @OA\Property(property="title", type="string", example="New post"),
 *                      @OA\Property(property="description", type="string", example="new post"),
 *                      @OA\Property(property="chat_title", type="string", example="New post"),
 *                      @OA\Property(property="chat_body", type="string", example="My post"),
 *                      @OA\Property(property="post_tags", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", example="books"),
 *                              @OA\Property(property="1", type="string", example="reading"),
 *                              @OA\Property(property="2", type="string", example="stay positive"),)),
 *                      @OA\Property(property="quote_text", type="string", example="New post"),
 *                      @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                      @OA\Property(property="keep_reading", type="integer", example=1),
 *                      @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                              @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                              @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *                      @OA\Property(property="video ", type="string", format="byte", example=""),
 *                      @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                      @OA\Property(property="post_type ", type="string", example="text"),
 *                      @OA\Property(property="url_videos ", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="0", type="string", example="facebook.com"),
 *                              @OA\Property(property="1", type="string", example="google.com"),
 *                              @OA\Property(property="2", type="string", example="yahoo.com"),),),))
 *          ),
 *        ),
 *      ),
 *     ),
 * )
 *   ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

 /**
 * @OA\Get(
 * path="/post/{blog_id}/draft ",
 * summary="Get posts of blog which are drafted  ",
 * description=" A blog get scheduled posts",
 * operationId="getdraftpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="blog_username", type="string", example=""),
 *                  @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                  @OA\Property(property="blog_title", type="string", example=""),
 *                  @OA\Property(property="post_status", type="string", example="draft"),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *                  @OA\Property(property="link",type="string",example="facebook.com"),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *                  @OA\Property(property="traced_back_posts", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="post_id", type="integer", example=5),
 *                          @OA\Property(property="blog_id", type="integer", example=5),
 *                          @OA\Property(property="title", type="string", example="New post"),
 *                          @OA\Property(property="description", type="string", example="new post"),
 *                          @OA\Property(property="chat_title", type="string", example="New post"),
 *                          @OA\Property(property="chat_body", type="string", example="My post"),
 *                          @OA\Property(property="post_tags", type="array",
 *                              @OA\Items(
 *                                  @OA\Property(property="0", type="string", example="books"),
 *                                  @OA\Property(property="1", type="string", example="reading"),
 *                                  @OA\Property(property="2", type="string", example="stay positive"),)),
 *                          @OA\Property(property="quote_text", type="string", example="New post"),
 *                          @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                          @OA\Property(property="keep_reading", type="integer", example=1),
 *                          @OA\Property(property="images ", type="array",
 *                              @OA\Items(
 *                                  @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                                  @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                                  @OA\Property(property="2", type="string", format="byte", example="/images.png"),)),
 *                          @OA\Property(property="video ", type="string", format="byte", example=""),
 *                          @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                          @OA\Property(property="post_type ", type="string", example="text"),
 *                          @OA\Property(property="url_videos ", type="array",
 *                              @OA\Items(
 *                                  @OA\Property(property="0", type="string", example="facebook.com"),
 *                                  @OA\Property(property="1", type="string", example="google.com"),
 *                                  @OA\Property(property="2", type="string", example="yahoo.com"),),),))
 *          ),
 *       ),
 *      ),
 *     ),
 * ) ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/pinned",
 * summary="Make  a post is pinned in a blog",
 * description=" A blog change the post to be pinned",
 * operationId="pinnedpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),

 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/unpinned",
 * summary="Make  a post unpinned in a blog",
 * description=" A blog change the post to be pinned",
 * operationId="unpinnedpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),

 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

 /**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/change_status",
 * summary="change status of posts in a blog",
 * description=" A blog delete his/her post",
 * operationId="poststatus",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\RequestBody(
 *    required=true,
 *    description="Change status of post from private/queue/draft/submission/scheduling to be pusblished",
 *    @OA\JsonContent(
 *      required={"post_status"},
 *       @OA\Property(property="post_status", type="string", example="queue"),
 *       @OA\Property(property="scheulding_time",type="date-time",example=""),
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
 /**
 * @OA\Post(
 * path="/post/submission/{blog_id}",
 * summary="sumbit new post to another blog",
 * description="A blog can  submit a post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="blog_id who is submited ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *     in text type :description or title are required, at least one of them ,keep reading is optinal
 *     in image type : at least one uplaoded image,
 *     in quote type:  quote_text is required, quote_body is optinal
 *     in video type:  video is required, url_videos are optinal
 *     in link type: link is required
 *     is general : all fields can be given, to be general at least two different field of types should given",
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="link",type="string",example="facebook.com"),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *      @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                      @OA\Property(property="1", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                      @OA\Property(property="2", type="string", format="byte", example="TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"),
 *                  )
 *           ),
 *       @OA\Property(property="video ", type="string", format="byte", example=""),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                  )
 *       )
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *      ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *     )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *     )
 *  ),
 * )
 */
}
