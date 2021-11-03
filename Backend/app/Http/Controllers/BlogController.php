<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
        //
/**
 * @OA\Post(
 * path="/new/blog",
 * summary="create blog",
 * description=" Creating a secondary blog",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * operationId="createBlog",
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    title : the title of the new blog ,
 *    blog_username : the blog_username will be used in the blog URL,
 *    password : the password of the new blog if there's a one",
 *    @OA\JsonContent(
 *      required={"title","blog_username"},
 *      @OA\Property(property="title", type="string", example="my blog"),
 *      @OA\Property(property="blog_username", type="string", example="CairoBlogs"),
 *      @OA\Property(property="password", type="string",format="password", example="<df1212V>"),
 *                )
 *               ),
 * @OA\Response(
 *    response=200,
 *    description="Successfully created a blog",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
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
 * path="/blog/{blog_id}",
 * summary="delete blog",
 * description=" Deleting a secondary blog",
 * tags={"Blogs"},
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

}
