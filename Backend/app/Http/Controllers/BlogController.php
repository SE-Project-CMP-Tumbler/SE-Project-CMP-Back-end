<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
/**
 * @OA\Get(
 * path="/blog/{blog_id}",
 * summary="Get general information of a specific blog",
 * description="Returns the general information of a specific blog",
 * operationId="getBlog",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="The id of the blog whose information will be retrieved",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", 
 *          @OA\Property(property="id", type="integer", example=2026),
 *          @OA\Property(property="username", type="string", example="newinvestigations"),
 *          @OA\Property(property="avatar", type="string", format="byte", example=""),
 *          @OA\Property(property="avatar_shape", type="string", example="square"),
 *          @OA\Property(property="header_image", type="string", format="byte", example=""),
 *          @OA\Property(property="title", type="string", example="My 1st Blog"),
 *          @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),),)),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}),)), 
 * 
 * @OA\Response(
 *  response=404,
 *  description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"The blog id specified was not found"}),)), 
 * 
 * @OA\Response(
 *  response=500,
 *  description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal server error"}),)), 
 * 
 * )
 */ 
 /**
 * @OA\Get(
 * path="/blog",
 * summary="Get all blogs of user",
 * description="Returns the general information of a specific blog",
 * operationId="getBlogs",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", 
 *           @OA\Property(property="blogs",type="array",
 *             @OA\Items(
 *                    @OA\Property(property="id", type="integer", example=2026),
 *                    @OA\Property(property="username", type="string", example="newinvestigations"),
 *                    @OA\Property(property="avatar", type="string", format="byte", example=""),
 *                    @OA\Property(property="avatar_shape", type="string", example="square"),
 *                    @OA\Property(property="header_image", type="string", format="byte", example=""),
 *                    @OA\Property(property="title", type="string", example="My 1st Blog"),
 *                    @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),))) 
 *      )),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}),)), 
 * 
 * @OA\Response(
 *  response=404,
 *  description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found), 
 * 
 * @OA\Response(
 *  response=500,
 *  description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal server error"}),)), 
 * 
 * )
 */   
/**
 * @OA\Post(
 * path="/blog",
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
 *      @OA\Property(property="password", type="string",format="password", example="123"),
 *                )
 *               ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
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
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * )
 */
/**
 * @OA\Delete(
 * path="/blog/{blog_id}",
 * summary="delete blog",
 * description="Deleting a secondary blog",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id of followed blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
/**
 * @OA\Get(
 * path="/blog/check_out_blogs",
 * summary="Check out another random blogs",
 * description="Returns  another blogs ",
 * operationId="getcheckoutBlogs",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", 
 *           @OA\Property(property="blogs",type="array",
 *             @OA\Items(
 *                    @OA\Property(property="id", type="integer", example=2026),
 *                    @OA\Property(property="username", type="string", example="newinvestigations"),
 *                    @OA\Property(property="avatar", type="string", format="byte", example=""),
 *                    @OA\Property(property="avatar_shape", type="string", example="square"),
 *                    @OA\Property(property="header_image", type="string", format="byte", example=""),
 *                    @OA\Property(property="title", type="string", example="My 1st Blog"),
 *                    @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),))) 
 *      )),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}),)), 
 * 
 * @OA\Response(
 *  response=404,
 *  description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"The blog id specified was not found"}),)), 
 * 
 * @OA\Response(
 *  response=500,
 *  description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal server error"}),)), 
 * 
 * )
 */
/**
 * @OA\Get(
 * path="/blog/trending",
 * summary="Get blogs which are trending",
 * description="Returns  trending blogs ",
 * operationId="gettrendingBlogs",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object", 
 *           @OA\Property(property="blogs",type="array",
 *             @OA\Items(
 *                    @OA\Property(property="id", type="integer", example=2026),
 *                    @OA\Property(property="username", type="string", example="newinvestigations"),
 *                    @OA\Property(property="avatar", type="string", format="byte", example=""),
 *                    @OA\Property(property="avatar_shape", type="string", example="square"),
 *                    @OA\Property(property="header_image", type="string", format="byte", example=""),
 *                    @OA\Property(property="title", type="string", example="My 1st Blog"),
 *                    @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),))) 
 *      )),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}),)), 
 * 
 * @OA\Response(
 *  response=404,
 *  description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"}),)), 
 * 
 * @OA\Response(
 *  response=500,
 *  description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal server error"}),)), 
 * 
 * )
 */
}
