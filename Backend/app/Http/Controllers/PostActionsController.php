<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostActionsController extends Controller
{
 /**
 * @OA\Post(
 * path="/post/reply/{post_id}",
 * summary="add reply on a post",
 * description="send note/reply on a specific post on any blog",
 * operationId="addPostNote",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to reply on",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="intger",
 *    )
 *  ),
 *  @OA\RequestBody(
 *   description="reply_text: reply text body",
 *   @OA\JsonContent(
 *       @OA\Property(property="reply_text", type="string", example="Good Work"),
 *     ),
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Reply",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *        @OA\Property(property="blog_object", type="array",
 *          @OA\Items(
 *            @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *            @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *            @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *            @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *            @OA\Property(property="blog_id", type="integer", example=1032),
 *            @OA\Property(property="followed", type="boolean", example=false),
 *            @OA\Property(property="reply_text", type="string", example="this is my last reply"),
 *            @OA\Property(property="reply_time", type="date-time", example="02-02-2021"),
 *            @OA\Property(property="reply_id", type="integer", example=5),
 *          )
 *       ),
 *    ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 *   @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))
 * ),
 *   @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 * )
 */

 /**
 * @OA\Get(
 * path="/post/like/{blog_id}/{post_id}",
 * description="check if specific blog likes specific post",
 * operationId="checkBlogLikesPost",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *   name="blog_id",
 *   description="blog id",
 *   required=true,
 *   in="path",
 *    @OA\Schema(
 *       type="intger",
 *    )
 * ),
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="post id",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="integer",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
 *       @OA\Property(property="response",type="object",
 *         @OA\Property(property="like_status", type="boolean", example=false),
 *       ),
 *   ),
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 * )
 */

 /**
 * @OA\Post(
 * path="/post/like/{post_id}",
 * summary="add like on a post",
 * description="send like on a specific post on any blog via this api route",
 * operationId="addPostLike",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to reply on",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="int",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *    ),
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 * )
 */

 /**
 * @OA\Delete(
 * path="/post/reply/{reply_id}",
 * summary="delete a specific note/repy from a post",
 * description="each blog can delete any note/repy from their posts, using this api route",
 * operationId="deletePostNote",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="reply_id",
 *    description="for the reply to be deleted",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="int",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Post Reply Deletion",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *    ),
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 *  @OA\Response(
 *   response=404,
 *   description="Not Found",
 *   @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
 *   ),
 *  ),
 * )
 */


 /**
 * @OA\Delete(
 * path="/post/like/{post_id}",
 * description="delete a specific like from a post",
 * operationId="deletePostLike",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to delete that like from",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="integer",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like Deletion",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *    ),
 *  ),
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
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 *  @OA\Response(
 *   response=404,
 *   description="Not Found",
 *   @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
 *   ),
 *  )
 * )
 */
}
