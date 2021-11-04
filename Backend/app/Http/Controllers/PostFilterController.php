<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostFilterController extends Controller
{
   
/**
 * @OA\Get(
 * path="/post/text ",
 * summary="Get posts  with  type text   ",
 * description=" A blog get text posts",
 * operationId="textpost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *        @OA\Property(property="post_type", type="string", example="text"),
 *        @OA\Property(property="title", type="string", example="New post"),
 *        @OA\Property(property="description", type="string", example="new post"),
 *        @OA\Property(property="keep_reading", type="integer", example=5),
 *        @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
 /**
 * @OA\Get(
 * path="/post/chat ",
 * summary="Get posts  with  type chat   ",
 * description=" A blog get text posts",
 * operationId="chatpost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *           @OA\Property(property="post_type", type="string", example="chat"),
 *           @OA\Property(property="chat_title", type="string", example="New post"),
 *           @OA\Property(property="chat_body", type="string", example="new post"),
 *           @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
 /**
 * @OA\Get(
 * path="/post/quote ",
 * summary="Get posts  with  type quote   ",
 * description=" A blog get quote posts",
 * operationId="quotepost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *    @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *    @OA\Property(property="response",type="object",
 *    @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *        @OA\Property(property="post_type", type="string", example="quote"),
 *        @OA\Property(property="quote_title", type="string", example="New post"),
 *        @OA\Property(property="quote_resouce", type="string", example="new post"),
 *        @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 * 
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
 /**
 * @OA\Get(
 * path="/post/image ",
 * summary="Get posts  with  type image   ",
 * description=" A blog get image posts",
 * operationId="imagepost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *        @OA\Property(property="post_type", type="string", example="image"),
 *        @OA\Property(property="images ", type="array",
 *        @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                      
 *                  )
 *           ),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
/**
 * @OA\Get(
 * path="/post/video ",
 * summary="Get posts  with  type video   ",
 * description=" A blog get video posts",
 * operationId="videopost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *              @OA\Property(property="post_id", type="integer",  example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *              @OA\Property(property="tags",type="array",
 *                 @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *        @OA\Property(property="video ", type="string", format="byte", example="/video.mov"),
 *        @OA\Property(property="post_type", type="string", example="video"),
 *        @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"}) )
 *     )
 * )
 *   
 */

/**
 * @OA\Get(
 * path="/post/audio ",
 * summary="Get posts  with  type audio   ",
 * description=" A blog get aduio posts",
 * operationId="audiopost",
 * tags={"Posts"},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *          @OA\Property(property="post_id", type="integer", example=5),
 *          @OA\Property(property="blog_id", type="integer", example=5),
 *          @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"))),
 *                  @OA\Property(property="audio ", type="string", format="byte", example="/audio.mp3"),
 *                  @OA\Property(property="post_type", type="string", example="aduio"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
 *        )
 *     )
 * )
 *   
 */
/**
 * @OA\Get(
 * path="/post/radar",
 * summary="Get random posts ",
 * description=" A blog get  post",
 * operationId="radarpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
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
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),)
 *   ), 
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */

/**
 * @OA\Get(
 * path="/post/random_posts",
 * summary="Get  random posts",
 * description=" A blog get random posts",
 * operationId="randompost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
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
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),))),
 *                 
 *       ),
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
/**
 * @OA\Get(
 * path="/post/trending",
 * summary="Get  trending posts",
 * description=" A blog get trending posts",
 * operationId="trendingpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
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
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),))),
 *                 
 *       ),
 * ),
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *   
 */
}
