<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlockBlogController extends Controller
{
    //
    /**
 * @OA\Post(
 * path="/block/{blog_id}",
 * summary="block blog",
 * description=" Primary blog block another blog",
 * operationId="followblog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * )
 */
/**
 * @OA\Delete(
 * path="/block/{blog_id}",
 * summary="unblock blog",
 * description=" Primary blog unblock another blog",
 * operationId="unfollowblog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * )
 */
/**
 * @OA\Get(
 * path="/block",
 * summary="blocked blogs",
 * description=" Primary blog  get all blocked blogs",
 * operationId="followersblog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *        @OA\Property(property="blocked blog", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),       
 *                  )
 *              ),
 *      )
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * )
 */

}
