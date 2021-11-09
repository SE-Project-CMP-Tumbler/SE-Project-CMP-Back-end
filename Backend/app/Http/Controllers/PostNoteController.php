<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostNoteController extends Controller
{

 /**
 * @OA\Get(
 * path="/post_notes/{post_id}",
 * summary="Gets all notes for a specific post by post id",
 * description="Returns a list of all notes attached to a specific post",
 * operationId="getPostNotesByPostId",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="post_id",
 *          description="The Post Id for the notes to be retrieved",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *              @OA\Property(property="likes", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="followed", type="boolean", example=false))),
 *
 *              @OA\Property(property="replies", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=2),
 *                      @OA\Property(property="reply_id", type="integer", example=5),
 *                      @OA\Property(property="reply_time", type="date-time", example="02-02-2012"),
 *                      @OA\Property(property="reply_text", type="string", example="What an amazing post!"))),
 *
 *              @OA\Property(property="reblogs", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="post_id", type="integer", example=5),
 *                      @OA\Property(property="blog_avatar", type="string", format="byte", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=2),
 *                      @OA\Property(property="reblog_content", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>")))))),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A post with the specified ID was not found"}))),
 * )
 */
}
