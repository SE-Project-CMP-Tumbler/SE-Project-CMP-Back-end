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
 *       @OA\Property(property="post_type", type="string", example="general"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *   )),
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