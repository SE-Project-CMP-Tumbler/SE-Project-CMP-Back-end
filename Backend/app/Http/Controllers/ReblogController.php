<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReblogController extends Controller
{
/**
 * @OA\Post(
 * path="/reblog/{blog_id}/{parent_post_id}",
 * summary="Creates a new reblog post",
 * description="Create a new reblog whose parent post is the direct post it is reblogged from",
 * operationId="createReblog",
 * tags={"Reblogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *      name="blog_id",
 *      description="Blog id of the blog creating the reblog",
 *      required=true,
 *      in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Parameter(
 *      name="parent_post_id",
 *      description="Post id of the direct post the reblog is reblogged from",
 *      required=true,
 *      in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=false,
 *    description= "Reblog Request can contain different data types, however, all fields are optional" ,
 *    @OA\JsonContent(
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="title", type="string", example="New post"),
 *       @OA\Property(property="description", type="string", example="new post"),
 *       @OA\Property(property="chat_title", type="string", example="New post"),
 *       @OA\Property(property="chat_body", type="string", example="My post"),
 *       @OA\Property(property="quote_text", type="string", example="New post"),
 *       @OA\Property(property="quote_resouce", type="string", example="My post"),
 *       @OA\Property(property="keep_reading", type="integer", example=1),
 *       @OA\Property(property="post_tags", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="books"),
 *              @OA\Property(property="1", type="string", example="reading"),
 *              @OA\Property(property="2", type="string", example="stay positive"),)),
 *       @OA\Property(property="images ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *              @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *              @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 * 
 *       @OA\Property(property="video ", type="string", format="byte", example=""),
 *       @OA\Property(property="audio ", type="string", format="byte", example=""),
 *       @OA\Property(property="post_type ", type="string", example="text"),
 *       @OA\Property(property="url_videos ", type="array",
 *          @OA\Items(
 *              @OA\Property(property="0", type="string", example="facebook.com"),
 *              @OA\Property(property="1", type="string", example="google.com"),
 *              @OA\Property(property="2", type="string", example="yahoo.com"),))),),
 * 
 * @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *     )
 * ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),),),
 * )
 * 
 */
}