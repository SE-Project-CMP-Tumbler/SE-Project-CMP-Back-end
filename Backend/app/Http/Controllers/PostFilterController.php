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
 *          @OA\Items(
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="text"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
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
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <p>It's the weapon that'd end the humanity!!</p> <p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="chat"),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> Your browser does not support the video tag.<p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="post_type", type="string", example="quote"),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <p>#AI #humanity #freedom</p></div><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''>"  
 *               ),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="image"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="post_type", type="string", example="video"),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *   @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *               @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <p>It's the weapon that'd end the humanity!!</p> Your browser does not support the video tag.<source src='movie.mp4' type='video/mp4'><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="audio"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *   @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *   @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 *  @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 * path="/post/ask",
 * summary="Get  asked posts",
 * description=" A blog get asked posts",
 * operationId="askedpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *  @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_type", type="string", example="ask"),
 *              @OA\Property(property="question_body", type="string", example="How are you?"),
 *              @OA\Property(property="question_id", type="integer", example=3),
 *              @OA\Property(property="flag", type="boolean", example=false),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 * path="/post/dashboad",
 * summary="Get  dashboad post",
 * description=" A blog get dashboad posts",
 * operationId="dashboardpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *  @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="post_status", type="string", example="published"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="post_body", type="string", example=
 * "<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"  
 *               ),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *          ),
 *   
 *       ),
 *        
 * ),
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
 