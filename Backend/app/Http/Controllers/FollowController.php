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
 *    )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", example={"follower_id": "1", "msg":"You Follow Ahmed Successfully"})
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
 * description=" Primary blog  get all followers",
 * operationId="followersblog",
 * tags={"Follow Blogs"},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", example={"followers": 
 *                    {
 *                      { "blog_id": 1,
 *                        "blog_name":"Radwa"
 *                       } ,
 *                       { "blog_id": 2,
 *                        "blog_name":"Noran"
 *                       } 
 *                   
 *                     }, 
 *               })
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
 * summary="following's blog",
 * description=" Primary blog  get all followings",
 * operationId="followingblog",
 * tags={"Follow Blogs"},
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", example={"followings": 
 *                    {
 *                      { "blog_id": 1,
 *                        "blog_name":"Radwa"
 *                       } ,
 *                       { "blog_id": 2,
 *                        "blog_name":"Noran"
 *                       } 
 *                   
 *                     }, 
 *               })
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
