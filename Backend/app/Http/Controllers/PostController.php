<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Http\Request;

class PostController extends Controller
{


/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}",
 * summary="Edit a new post",
 * description="A blog can edit post",
 * operationId="editpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *     in text type :description or title are required, at least one of them ,keep reading is optinal
 *     in image type : at least one uplaoded image,
 *     in chat type : chat_body is required ,chat_title is optional
 *     in quote type:  quote_text is required, quote_body is optinal
 *     in video type:  video is required , url_videos are optinal
 *     in audio type: audio is required
 *     in link type:  link is required
 *     is general : all fields can be given , to be general at least two different field of types should given" ,
 *    @OA\JsonContent(
 *      required={"post_status","post_type"},
 *      @OA\Property(property="post_status", type="string", example="published"),
 *      @OA\Property(property="post_type", type="string", example="general"),
 *      @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"))),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="post_id", type="integer", example=5),
 *       @OA\Property(property="pinned", type="boolean", example=false),
 *       @OA\Property(property="blog_id", type="integer", example=5),
 *       @OA\Property(property="blog_username", type="string", example=""),
 *       @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *       @OA\Property(property="blog_title", type="string", example=""),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="question_body", type="string", example="How are you?"),
 *       @OA\Property(property="question_id", type="integer", example=3),
 *       @OA\Property(property="question_flag", type="boolean", example=false),
 *       @OA\Property(property="blog_id_asking", type="integer", example=""),
 *       @OA\Property(property="blog_username_asking", type="string", example=""),
 *       @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *       @OA\Property(property="blog_title_asking", type="string", example=""),
 *       @OA\Property(property="post_type", type="string", example="general"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *       @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *              @OA\Property(property="post_type", type="string", example="text"),
 *              @OA\Property(property="post_body", type="string", example="<div><h1>The title</h1> <p>a post of type text.</p></div>"),
 *              @OA\Property(property="question_body", type="string", example="How are you?"),
 *              @OA\Property(property="question_id", type="integer", example=3),
 *              @OA\Property(property="question_flag", type="boolean", example=false),
 *              @OA\Property(property="blog_id_asking", type="integer", example=""),
 *              @OA\Property(property="blog_username_asking", type="string", example=""),
 *              @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *              @OA\Property(property="blog_title_asking", type="string", example=""),))),),),
 *@OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
    /**
     * Update an existing post
     *
     * @param int $post_id
     * @param \App\Http\Requests\UpdatePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($post_id, UpdatePostRequest $request)
    {
        // if (preg_match('([0-9]+$)', $post_id) == false) {
        //     return $this->general_response("", "The post id should be numeric.", "422");
        // }
        $post = Post::where('id', $post_id)->first();
        if ($post == null) {
            return $this->general_response("", "This post was not found", "404");
        }

        // $this->authorize('update', $post);
        $post->update([
            'status' => $request->post_status ?? $post->status,
            'published_at' => $request->post_time ?? $post->published_at,
            'body' => $request->post_body ?? $post->body,
            'type' => $request->post_type ?? $post->type,
            'pinned' => $request->pinned ?? $post->pinned
        ]);

        return $this->general_response(new PostResource($post), "ok");
    }
/**
 * @OA\Delete(
 * path="/post/{post_id}/{blog_id}",
 * summary="Delete post",
 * description=" A blog delete his/her post",
 * operationId="deletepost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
    /**
     * Delte a post
     *
     * @param int $post_id
     * @param int $blog_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if (empty($post)) {
            return $this->general_response("", "This post doesn't exist", "404");
        }

        $this->authorize('delete', $post);
        $post->delete();
        return $this->general_response("", "ok");
    }
/**
 * @OA\Get(
 * path="/post/{post_id}",
 * summary="Get specific post",
 * description="A blog get post",
 * operationId="getapost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *   @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="post_status", type="string", example="published"),
 *      @OA\Property(property="post_id", type="integer", example=5),
 *      @OA\Property(property="blog_id", type="integer", example=5),
 *      @OA\Property(property="blog_username", type="string", example=""),
 *      @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *      @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *      @OA\Property(property="blog_title", type="string", example=""),
 *      @OA\Property(property="pinned", type="boolean", example=false),
 *      @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *      @OA\Property(property="question_body", type="string", example="How are you?"),
 *      @OA\Property(property="question_id", type="integer", example=3),
 *      @OA\Property(property="question_flag", type="boolean", example=false),
 *      @OA\Property(property="blog_id_asking", type="integer", example=""),
 *      @OA\Property(property="blog_username_asking", type="string", example=""),
 *      @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *      @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *      @OA\Property(property="blog_title_asking", type="string", example=""),
 *      @OA\Property(property="post_type", type="string", example="general"),
 *      @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *      @OA\Property(property="traced_back_posts", type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *              @OA\Property(property="question_body", type="string", example="How are you?"),
 *              @OA\Property(property="question_id", type="integer", example=3),
 *              @OA\Property(property="question_flag", type="boolean", example=false),
 *              @OA\Property(property="blog_id_asking", type="integer", example=""),
 *              @OA\Property(property="blog_username_asking", type="string", example=""),
 *              @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *              @OA\Property(property="blog_title_asking", type="string", example=""),))
 *     ),
 * )
 *   ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
    /**
     * Retrieve a specific post
     *
     * @param int $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($post_id, Request $request)
    {
        // $request->merge(['post_id' => $request->route('post_id')]);
        // $request->validate([
        //     'post_id' => 'required|numeric'
        // ]);

        $post = Post::where('id', $post_id)->first();
        if ($post == null) {
            return $this->general_response("", "This post was not found", "404");
        }
        return $this->general_response(new PostResource($post), "ok");
    }
 /**
 * @OA\Post(
 * path="/post/{blog_id}",
 * summary="create new post",
 * description="A blog can create new post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *     in text type :description or title are required, at least one of them ,keep reading is optinal
 *     in image type : at least one uplaoded image,
 *     in chat type : chat_body is required, chat_title is optional
 *     in quote type:  quote_text is required, quote_body is optinal
 *     in video type:  video is required, url_videos are optinal
 *     in audio type: audio is required
 *     is general : all fields can be given, to be general at least two different field of types should given",
 *    @OA\JsonContent(
 *      required={"post_status","post_type"},
 *      @OA\Property(property="post_status", type="string", example="published"),
 *      @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *      @OA\Property(property="post_type", type="string", example="general"),
 *      @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *       @OA\Property(property="post_id", type="integer", example=5),
 *       @OA\Property(property="blog_id", type="integer", example=5),
 *       @OA\Property(property="blog_username", type="string", example=""),
 *       @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *       @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *       @OA\Property(property="blog_title", type="string", example=""),
 *       @OA\Property(property="pinned", type="boolean", example=false),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="post_type", type="string", example="general"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *        ),
 *      ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *     )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *     )
 *  ),
 * )
 */
    /**
     * Creates a new post
     *
     * @param \App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostRequest $request)
    {
        $published_at = ($request->post_time == null && ($request->post_status == 'published' || $request->post_status == 'private')) ? now() : $request->post_time;

        $post = Post::create([
            'status' => $request->post_status,
            'published_at' => $published_at,
            'body' => $request->post_body,
            'type' => $request->post_type,
            'blog_id' => $request->blog_id
        ]);

        //need to create the tags

        return $this->general_response(new PostResource($post), "ok");
    }
/**
 * @OA\Get(
 * path="/post/submission/{blog_id}",
 * summary="Get posts of blog which are submitted",
 * description="A blog get submitted posts",
 * operationId="getsubmissionposts",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *   @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *      @OA\Property(property="posts",type="array",
 *          @OA\Items(
 *              @OA\Property(property="post_id", type="integer", example=5),
 *              @OA\Property(property="blog_id", type="integer", example=5),
 *              @OA\Property(property="blog_username", type="string", example=""),
 *              @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *              @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *              @OA\Property(property="blog_title", type="string", example=""),
 *              @OA\Property(property="post_status", type="string", example="submission"),
 *              @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *              @OA\Property(property="post_type", type="string", example="general"),
 *              @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *           ),
 *        ),
 *       ),
 *     ),
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */





/**
 * @OA\Get(
 * path="/post/{blog_id}",
 * summary="Get posts of blog which are published",
 * description="A blog get blog's posts",
 * operationId="getposts",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
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
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="blog_username", type="string", example=""),
 *                  @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                  @OA\Property(property="blog_title", type="string", example=""),
 *                  @OA\Property(property="pinned", type="boolean", example=false),
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *                  @OA\Property(property="question_body", type="string", example="How are you?"),
 *                  @OA\Property(property="question_id", type="integer", example=3),
 *                  @OA\Property(property="question_flag", type="boolean", example=false),
 *                  @OA\Property(property="blog_id_asking", type="integer", example=""),
 *                  @OA\Property(property="blog_username_asking", type="string", example=""),
 *                  @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *                  @OA\Property(property="blog_title_asking", type="string", example=""),
 *                  @OA\Property(property="post_type", type="string", example="general"),
 *                  @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *                  @OA\Property(property="traced_back_posts", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="post_id", type="integer", example=5),
 *                      @OA\Property(property="blog_id", type="integer", example=5),
 *                      @OA\Property(property="blog_username", type="string", example="lifeisnotfair"),
 *                      @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *                      @OA\Property(property="post_type", type="string", example="general"),
 *                      @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *                      @OA\Property(property="question_body", type="string", example=""),
 *                      @OA\Property(property="question_id", type="integer", example=""),
 *                      @OA\Property(property="question_flag", type="boolean", example=false),
 *                      @OA\Property(property="blog_id_asking", type="integer", example=""),
 *                      @OA\Property(property="blog_username_asking", type="string", example=""),
 *                      @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *                      @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *                      ))
 *          ),
 *        ),
 *      ),
 *     ),
 * )
 *   ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

 /**
 * @OA\Get(
 * path="/post/{blog_id}/draft",
 * summary="Get posts of blog which are drafted",
 * description="A blog get scheduled posts",
 * operationId="getdraftpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
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
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="blog_username", type="string", example=""),
 *                  @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                  @OA\Property(property="blog_title", type="string", example=""),
 *                  @OA\Property(property="post_status", type="string", example="draft"),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *                  @OA\Property(property="post_type", type="string", example="general"),
 *                  @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *                  @OA\Property(property="traced_back_posts", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="post_id", type="integer", example=5),
 *                          @OA\Property(property="blog_id", type="integer", example=5),
 *                          @OA\Property(property="post_time",type="date_time",example="02-02-2011"),
 *                          @OA\Property(property="post_type", type="string", example="general"),
 *                          @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *                          @OA\Property(property="question_body", type="string", example="How are you?"),
 *                          @OA\Property(property="question_id", type="integer", example=3),
 *                          @OA\Property(property="question_flag", type="boolean", example=false),
 *                          @OA\Property(property="blog_id_asking", type="integer", example=""),
 *                          @OA\Property(property="blog_username_asking", type="string", example=""),
 *                          @OA\Property(property="blog_avatar_asking", type="string", format="byte", example=""),
 *                          @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *                          ))
 *          ),
 *       ),
 *      ),
 *     ),
 * ) ,
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/pinned",
 * summary="Make a post is pinned in a blog",
 * description="A blog change the post to be pinned",
 * operationId="pinnedpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),

 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * )
 */

/**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/unpinned",
 * summary="Make a post unpinned in a blog",
 * description="A blog change the post to be pinned",
 * operationId="unpinnedpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),

 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */

 /**
 * @OA\Put(
 * path="/post/{post_id}/{blog_id}/change_status",
 * summary="change status of posts in a blog",
 * description="A blog delete his/her post",
 * operationId="poststatus",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\RequestBody(
 *    required=true,
 *    description="Change status of post from private/draft to be pusblished",
 *    @OA\JsonContent(
 *      required={"post_status"},
 *       @OA\Property(property="post_status", type="string", example="published"),
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 */
 /**
 * @OA\Post(
 * path="/post/submission/{blog_id}",
 * summary="sumbit new post to another blog",
 * description="A blog can submit a post",
 * operationId="createpost",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="blog_id who is submited ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post Request has different types depeneds on post type :
 *     in text type :description or title are required, at least one of them ,keep reading is optinal
 *     in image type : at least one uplaoded image,
 *     in quote type:  quote_text is required, quote_body is optinal
 *     in video type:  video is required, url_videos are optinal
 *     in link type: link is required
 *     is general : all fields can be given, to be general at least two different field of types should given",
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *       @OA\Property(property="post_type", type="string", example="general"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *      ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *     )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *     )
 *  ),
 * )
 */
/**
 * @OA\Delete(
 * path="/post/submission/{post_id}",
 * summary="delete submission",
 * description=" A blog deletes a submitted post",
 * operationId="deleteSubmission",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="post_id",
 *          description="post_id of the submitted post to be deleted",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     ),
 *   @OA\Response(
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
 * @OA\Delete(
 * path="/post/submission",
 * summary="delete all submissions",
 * description="deleting all recieved submission posts",
 * operationId="deleteAllSubmissions",
 * tags={"Posts"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     ),
 *   @OA\Response(
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
}
