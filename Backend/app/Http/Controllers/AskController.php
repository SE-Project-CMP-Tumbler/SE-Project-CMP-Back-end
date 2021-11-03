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
 *    description="Question Asked body , flag show if anonymous or not ,true for anonymous and in case of anonymous , blog values will appear in response ",
 *     @OA\JsonContent(
 *         required={"question_body","flag"},
 *       @OA\Property(property="question_body", type="string", example="How are you?"),
 *       @OA\Property(property="flag", type="boolean", example=false)
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
 *                      @OA\Property(property="question_body", type="string", example="How are you?"),
 *                      @OA\Property(property="flag", type="boolean", example=false)
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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Unauthorized"})
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
 *    in text type :description or title are required, at least one of them ,keep reading is optinal
 *    in image type : at least one uplaoded image , 
 *    in chat type : chat_body is required ,chat_title is optional
 *    in quote type:  quote_text is required , quote_body is optinal 
 *    in video type:  video is required , url_videos are optinal
 *    in audio type: audio is required
 *    is genral : all fields can be given , to be genarl at least two different field of types should given" ,
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *       
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="chat_title", type="string", example="New post"),
 *       @OA\Property(property="chat_body", type="string", example="My post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="images ", type="array",
 *        @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),
 *                      
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
 *                         
 *                  )
 *       )
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
 *         @OA\Property(property="blog_id", type="integer", example=5),
 *         @OA\Property(property="title", type="string", example="New post"),
 *         @OA\Property(property="blog_avatar_asking", type="string", example="/storage/imgname2.extension"),
 *         @OA\Property(property="blog_avatar_shape_asking", type="string", example="circle"),
 *         @OA\Property(property="blog_username_asking", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_id_asking", type="integer", example=1032), 
 *         @OA\Property(property="question_body", type="string", example="How  are you?"), 
 *         @OA\Property(property="flag", type="bool", example=false),  
 *         @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example=""))),
 *        @OA\Property(property="description", type="string", example="new post"),
 *        @OA\Property(property="chat_title", type="string", example="New post"),
 *        @OA\Property(property="chat_body", type="string", example="My post"),
 *        @OA\Property(property="quote_text", type="string", example="New post"),
 *        @OA\Property(property="quote_resouce", type="string", example="My post"),
 *        @OA\Property(property="keep_reading", type="integer", example=1),
 *        @OA\Property(property="images ", type="array",
 *         @OA\Items(
 *                      @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                      @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),
 *                      
 *                  )
 *           ),
 *        @OA\Property(property="video ", type="string", format="byte", example=""),
 *        @OA\Property(property="audio ", type="string", format="byte", example=""),
 *        @OA\Property(property="post_type ", type="string", example="text"),
 *        @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 *     ),
 *  ),
 * ),
 *  
 * ),
 * 
 * 
 * 
 */

}
