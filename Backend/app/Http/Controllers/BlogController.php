<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Misc\Helpers\Config;
use App\Http\Misc\Helpers\Success;
use App\Models\Blog;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BlogCollection;
use App\Http\Requests\BlogRequest;
use App\Services\BlogService;

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
 *              type="integer")),
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
  * Get specific blog
  * @param \Blog  $blog
  * @return \json
 */
    public function show(Blog $blog)
    {
        return $this->general_response(new BlogResource($blog), "ok");
    }
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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))),
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
  * Get all blogs of user
  * @param \Request $request
  * @return \json
 */
    public function index(Request $request)
    {
        return $this->general_response(new BlogCollection($request->user()->blogs), "ok");
    }
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
  * Create a new blog for user
  * @param \BlogRequest $request
  * @return \json
 */
    public function store(BlogRequest $request)
    {
        $blogService = new BlogService();
        $user_id = $request->user()->id;
        if (! $blogService->uniqueBlog($request->blog_username)) {
            return $this->general_response("", "The blog username is already exists", "422");
        }

        if ($request->has('password')) {
            $blogService->createBlog($request->blog_username, $request->title, $user_id, $request->password);
        } else {
            $blogService->createBlog($request->blog_username, $request->title, $user_id);
        }
        return $this->general_response("", "ok");
    }
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
  * Delte a  blog for user
  * @param \Blog $blog
  * @return \json
 */
    public function delete(Request $request, Blog $blog)
    {

        $this->authorize('delete', $blog);
        $blog->delete();
        return $this->general_response("", "ok");
    }
/**
 * @OA\Get(
 * path="/blogs/check_out_blogs",
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
  *checkout Other Blogs for user
  * @param \Request $request
  * @return \json
 */
    public function checkOutOtherBlog(Request $request)
    {
        return $this->general_response(new BlogCollection(Blog::all()), "ok");
    }
/**
 * @OA\Get(
 * path="/blogs/trending",
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
 /**
  *Get Trending blogs
  * @param \Request $request
  * @return \json
 */
   //PAGINAGTE??
   //COMMENTS
   //functional docs
    public function getTrendingBlog(Request $request)
    {
        return $this->general_response(new BlogCollection(Blog::all()), "ok");
    }
/**
 * @OA\Get(
 * path="/blog/likes/{blog_id}",
 * description="get all likes of my current blog",
 * operationId="getBlogLikes",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="The id of the blog whose information will be retrieved",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *        @OA\Property(property="posts",type="array",
 *            @OA\Items(
 *               @OA\Property(property="blog_username", type="string", example="newinvestigations"),
 *               @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *               @OA\Property(property="blog_avatar_shape", type="string", example="square"),
 *               @OA\Property(property="post_id", type="integer", example=5),
 *               @OA\Property(property="blog_id", type="integer", example=5),
 *               @OA\Property(property="post_status", type="string", example="published"),
 *               @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *               @OA\Property(property="post_type", type="string", example="general"),
 *               @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *               @OA\Property(property="traced_back_posts", type="array",
 *                   @OA\Items(
 *                       @OA\Property(property="post_id", type="integer", example=5),
 *                       @OA\Property(property="blog_id", type="integer", example=5),
 *                       @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *                       @OA\Property(property="post_type", type="string", example="general"),
 *                       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),))
 *                 ),
 *       ),
 *      ),
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
  *Get Likes of specific blog
  * @param \Request $request
  * @return \json
 */
    // public function getLikeBlog(Request $request, Blog $blog)
    // {
    //     return $this->general_response(new PostCollection($blog->post)), "ok");
    // }
}
