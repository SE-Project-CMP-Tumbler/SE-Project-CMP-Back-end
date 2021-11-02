<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    

/**
 * @OA\Put(
 * path="/post/{post_id}",
 * summary="create new post",
 * description=" A blog can edit post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *    in text type :description or title are required, at least one of them ,keep reading is optinal
 *    in image type : at least one uplaoded image , 
 *    in chat type : chat_body is required ,chat_title is optional
 *    in quote type:  quote_text is required , quote_body is optinal 
 *    in video type:  video is required , url_videos are optinal
 *    in audio type: audio is required
 *    is genral : all fields can be given , to be genarl at least two different field of types should given" ,
 *    @OA\JsonContent(
 *         required={"post_status","post_type"},
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
 *      @OA\Property(property="url_videos ", type="array",
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
 * 
 * @OA\Response(
 *    response=200,
 *    description="Successful text post",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
 *       
 *       @OA\Property(property="response",type="object",
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 *     ),
 * ),
 * ),
 * ),
 *    @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     )
 * ),
 *   @OA\Response(
 *    response=500,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"error"})
 *        )
 *     )
 * ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 */
/**
 * @OA\Delete(
 * path="/post/{post_id}",
 * summary="Delete post",
 * description=" A blog delete his/her post",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */
/**
 * @OA\Get(
 * path="/post/{post_id}",
 * summary="Get specific post",
 * description=" A blog get  post",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *       @OA\Property(property="post_status", type="string", example="published"),
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 *     ),
 * )
 *   ,
 *  @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */
/**
 * @OA\Get(
 * path="/post/{blog_id}",
 * summary="Get posts of blog which are published",
 * description=" A blog get  blog 's posts",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *    @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *     
 *       @OA\Property(property="post_status", type="string", example="published"),
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 * 
 * 
 * 
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
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */

 /**
 * @OA\Post(
 * path="/post",
 * summary="create new post",
 * description=" A blog can create new post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *    in text type :description or title are required, at least one of them ,keep reading is optinal
 *    in image type : at least one uplaoded image , 
 *    in chat type : chat_body is required ,chat_title is optional
 *    in quote type:  quote_text is required , quote_body is optinal 
 *    in video type:  video is required , url_videos are optinal
 *    in audio type: audio is required
 *    is genral : all fields can be given , to be genarl at least two different field of types should given" ,
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
 *      @OA\Property(property="url_videos ", type="array",
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
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"error"})
 *        
 *     )
 * ),
 *   @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful text post",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *        @OA\Property(property="response", type="object",
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
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
/**
 * @OA\Get(
 * path="/post/{blog_id}/submission",
 * summary="Get posts of blog which are submitted",
 * description=" A blog get  submitted posts",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *    @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *     
 *       @OA\Property(property="post_status", type="string", example="submission"),
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 * 
 * 
 * 
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
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */
/**
 * @OA\Get(
 * path="/post/{blog_id}/queue",
 * summary="Get posts of blog which are queued ",
 * description=" A blog get queued posts",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *    @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *     
 *       @OA\Property(property="post_status", type="string", example="queue"),
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 * 
 * 
 * 
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
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */
/**
 * @OA\Get(
 * path="/post/{blog_id}/draft",
 * summary="Get posts of blog which are drafted ",
 * description=" A blog get drafted posts",
 * operationId="post",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *     @OA\JsonContent(
 *    @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *       @OA\Property(property="posts",type="array",
 *        @OA\Items(
 *     
 *       @OA\Property(property="post_status", type="string", example="draft"),
 *        @OA\Property(property="title", type="string", example="New post"),
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
 *      @OA\Property(property="url_videos ", type="array",
 *            @OA\Items(
 *                      @OA\Property(property="0", type="string", example="facebook.com"),
 *                      @OA\Property(property="1", type="string", example="google.com"),
 *                      @OA\Property(property="2", type="string", example="yahoo.com"),
 *                         
 *                  ),
 *       ),
 * 
 * 
 * 
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
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *   
 */

  

}
