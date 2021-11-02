<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostNoteController extends Controller
{
 
 /**
 * @OA\Get(
 * path="/post_notes/{id}",
 * summary="Gets all notes for a specific post by post id",
 * description="Returns a list of all notes attached to a specific post",
 * operationId="getPostNotesByPostId",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    description="ID of the post",
 *    in="path",
 *    name="post_id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int"
 *    )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="list of notes",
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
 *                      @OA\Property(property="blog_url", type="string", example="https://www.tumblr.com/blog/view/radwa-ahmed213"),
 *                      @OA\Property(property="followed", type="boolean", example=false)
 *                  )
 *              ),
 *              
 *              @OA\Property(property="replies", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=2),
 *                      @OA\Property(property="blog_url", type="string", example="https://www.tumblr.com/blog/view/radwa-ahmed213"),
 *                      @OA\Property(property="reply_content", type="string", example="What an amazing post!")
 *                  )
 *              ),
 * 
 *              @OA\Property(property="reblogs", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=2),
 *                      @OA\Property(property="blog_url", type="string", example="https://www.tumblr.com/blog/view/radwa-ahmed213"),
 *                      @OA\Property(property="reblog_content", type="string", example=""),
 *                      @OA\Property(property="reblog_tags", type="string", example="[]"), 
 *                      @OA\Property(property="reblog_type", type="string", example="video")          
 *                  )
 *              )
 *          )
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="A post with the specified ID was not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A post with the specified ID was not found"})
 *        )
 *     )
 * )
 */
}
