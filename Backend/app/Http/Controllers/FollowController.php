<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowController extends Controller
{
 
 /**
 * @OA\Post(
 * path="/follow_blog/{id}",
 * summary="follow blog",
 * description=" Primary blog follow another blog",
 * operationId="followblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    description="ID of Following Blog ",
 *    in="path",
 *    name="blog_id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int"
 *    ),
 *  @OA\Parameter(
 *    description="ID of Following Blog ",
 *    in="path",
 *    name="blog_id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int"
 *    )
 * ),
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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"}),
 *       @OA\Property(property="response", type="object", example={"blog_avatar":"/storage/imgname2.extension",
 *        "blog_avatar_shape":"circle","blog_username":"radwa-ahmed213","blog_id":1032})
 *       
 *        )
 *     )
 * )
 */
/**
 * @OA\Delete(
 * path="/unfollow_blog/{id}",
 * summary="unfollow blog",
 * description=" Primary blog unfollow another blog",
 * operationId="unfollowblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    description="ID of Unfollowing Blog ",
 *    in="path",
 *    name="blog_id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int"
 *    )
 * ),
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
 * path="/followers",
 * summary="follower's blog",
 * description=" Primary blog  get all his/her followers",
 * operationId="followersblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *        @OA\Property(property="followers", type="array",
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

/**
 * @OA\Get(
 * path="/followings",
 * summary="followings's blog",
 * description=" Primary blog  get all his/her followings",
 * operationId="followingblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",        
 *         @OA\Property(property="followings", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),       
 *                  )
 *              ),
 *    )
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
 * path="/followed_by/{id}",
 * summary="followed_by blog",
 * description=" Check if followed by blog",
 * operationId="followingblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *         @OA\Property(property="response", type="obeject",example={"followed":true},
 *                 
 *              ),
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
