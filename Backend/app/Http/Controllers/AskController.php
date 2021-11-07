<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AskController extends Controller
{
    //
    
 /**
 * @OA\Post(
 * path="/ask/{blog_id}",
 * summary="ask blog",
 * description=" Primary blog ask another blog",
 * operationId="askblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id of asking blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\RequestBody(
 *    required=true,
 *    description="Question Asked body , question_flag show if anonymous or not ,true for anonymous and in case of anonymous , blog values will appear in response ",
 *     @OA\JsonContent(
 *         required={"question_body","question_flag"},
 *       @OA\Property(property="question_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *       @OA\Property(property="question_flag", type="boolean", example=false)
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        @OA\Property(property="response", type="object",
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032), 
 *                      @OA\Property(property="question_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *                      @OA\Property(property="question_flag", type="boolean", example=false),
 *                      @OA\Property(property="question_id", type="integer", example=3),
 *                    
 *         
 *       ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *     @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
/**
 * @OA\Post(
 * path="/answer/{question_id}",
 * summary="create new post",
 * description=" A blog can answer question",
 * operationId="asnwerquestion",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="question_id",
 *          description="question_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post/Answer Request has different types depeneds on answer type :
 *     
 *     is genral : all fields can be given , to be genarl at least two different field of types should given" ,
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *       
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="post_type", type="string", example="answer"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *    
 *    
 * 
 *   
 *    ),
 * ),
 * @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *         @OA\Property(property="post_id", type="integer", example=5),
 *         @OA\Property(property="post_time", type="date-time", example="02-02-2012"),
 *         @OA\Property(property="post_type", type="string", example="answer"),
 *         @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *         @OA\Property(property="question_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *         @OA\Property(property="question_flag", type="boolean", example=false),
 *         @OA\Property(property="blog_id", type="integer", example=5),
 *         @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.png"),
 *         @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *         @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_avatar_asking", type="string", example="/storage/imgname2.png"),
 *         @OA\Property(property="blog_avatar_shape_asking", type="string", example="circle"),
 *         @OA\Property(property="blog_username_asking", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_id_asking", type="integer", example=1032), 
 *         
 *         @OA\Property(property="question_id", type="integer", example=5), 
 *       
 *        
 *     ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 *  
 * ),
 * 
 * 
 * 
 */
 /**
 * @OA\Delete(
 * path="/ask/{ask_id}",
 * summary="ask blog",
 * description=" Primary blog delete ask from another blog",
 * operationId="deleteaskblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="ask_id",
 *          description="ask_id of asking blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        
 *        
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
}
