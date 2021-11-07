<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

/**
 * @OA\Get(
 *  path="/blog/{blog_id}/notifications",
 *  operationId="getNotifications",
 *  tags={"Notifications"},
 *  security={ {"bearer": {} }},
 *  summary="retrieve blog's activity feed",
 *  description="Retrieve the activity items for a specific blog.",
 *  @OA\Parameter(
 *    in="path",
 *    name="blog_id",
 *    description="Any blog identifier.",
 *    required=true,
 *    example="123456789",
 *    @OA\Schema(
 *       type="int",
 *    )
 *  ),
 *  @OA\RequestBody(
 *   description="type: is a string parameter indicates which notification type to be recieved:
 *     like: a like on your post
 *     reply: a reply on your post
 *     follow: a new follower 
 *     reblog: a reblog of your post 
 *     ask: a new ask recieved 
 *     answer: an answered ask that you had sent
 *     all: to get all type of notifications",
 *   @OA\JsonContent(
 *         @OA\Property(property="type", type="string", example="all")
 *     ),
 *  ),
 *  @OA\Response(
 *    response=200,
 *    description="Successful Operation",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *      @OA\Property(property="response", type="object",
 *        @OA\Property(property="notifications", type="array",
 *          @OA\Items(
 *              @OA\Property(property="answers",type="array",
 *                 @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="answer_time", type="date-time", example=""),
 *                      @OA\Property(property="post_id",type="integer",example=5),
 *                      @OA\Property(property="post",type="object",
 *                           @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                          @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                          @OA\Property(property="video ", type="string", format="byte", example=""),
 *                          @OA\Property(property="audio ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/audname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/audname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/audname2.extension"),)),
 *                          @OA\Property(property="post_type ", type="string", example="text"),
 *              )
 *               )
 *              ),
 *              @OA\Property(property="asks",type="array",
 *                    @OA\Items(
 *                      @OA\Property(property="question_body", type="string", example="How are you?"),
 *                      @OA\Property(property="question_id", type="integer", example=5),
 *                      @OA\Property(property="flag", type="boolean", example=false),
 *                      @OA\Property(property="ask_time", type="date-time", example=""),
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                )
 *               ),
 *              @OA\Property(property="follows",type="array",
 *                @OA\Items(
 *                      @OA\Property(property="follow_time", type="date-time", example=""),
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                )
 *              ),
 *              @OA\Property(property="likes",type="array",
 *                 @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="followed", type="boolean", example=false),
 *                      @OA\Property(property="like_time", type="date-time", example=""),
 *                      @OA\Property(property="post_id",type="integer",example=5),
 *                      @OA\Property(property="post",type="object",
 *                           @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                          @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                          @OA\Property(property="video ", type="string", format="byte", example=""),
 *                          @OA\Property(property="audio ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/audname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/audname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/audname2.extension"),)),
 *                          @OA\Property(property="post_type ", type="string", example="text"),
 *                       ),),
 *                       ),
 *                    
 *                   ),
 *                   ),
 *               ),
 *              @OA\Property(property="replies",type="array",
 *                @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="followed", type="boolean", example=false),
 *                      @OA\Property(property="reply_text", type="string", example="this is my last reply"),
 *                      @OA\Property(property="reply_id", type="integer", example=5),
 *                      @OA\Property(property="reply_time", type="date-time", example=""),
 *                      @OA\Property(property="post",type="object",
 *                           @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                          @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                          @OA\Property(property="video ", type="string", format="byte", example=""),
 *                          @OA\Property(property="audio ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/audname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/audname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/audname2.extension"),)),
 *                          @OA\Property(property="post_type ", type="string", example="text"),
 *                      ),
 *                   ),
 *             ),
 *            @OA\Property(property="reblogs",type="array",
 *                @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="followed", type="boolean", example=false),
 *                      @OA\Property(property="reblog_time", type="date-time", example=""),
 *                      @OA\Property(property="reblog",type="object",
 *                           @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                          @OA\Property(property="images ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                          @OA\Property(property="video ", type="string", format="byte", example=""),
 *                          @OA\Property(property="audio ", type="array",
 *                          @OA\Items(
 *                           @OA\Property(property="0", type="string", format="byte",example="/storage/audname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/audname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/audname2.extension"),)),
 *                          @OA\Property(property="post_type ", type="string", example="text"),
 *                          ),),
 *                       ),
 *                    
 *                   ),
 *             ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 * ),
 *   @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
 *        )
 * ),
 * ),
 */
}



