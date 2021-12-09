<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Http\Requests\BlogPostRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * @OA\Put(
     * path="/post/{postId}",
     * summary="Edit a new post",
     * description="A blog can edit post",
     * operationId="editpost",
     * tags={"Posts"},
     * security={ {"bearer": {} }},
     *  @OA\Parameter(
     *          name="postId",
     *          description="Id of the post to be updated.",
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
     * @param int $postId
     * @param \App\Http\Requests\UpdatePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($postId, UpdatePostRequest $request)
    {
        //Validate the format of the post id
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post id should be numeric.", "422");
        }
        //Validate the existance of the post id
        $post = Post::where('id', $postId)->first();
        if (empty($post)) {
            return $this->generalResponse("", "This post was not found", "404");
        }

        $this->authorize('update', $post);

        //Extract tags of the unupdated post content.
        $postService = new PostService();
        $oldTags = $postService->extractTags($post->body);

        $post->update([
            'status' => $request->post_status ?? $post->status,
            'published_at' => $request->post_time ?? $post->published_at,
            'body' => $request->post_body ?? $post->body,
            'type' => $request->post_type ?? $post->type,
            'pinned' => $request->pinned ?? $post->pinned
        ]);

        //Extract tags of the new updated post content.
        $newTags = $postService->extractTags($post->body);

        //Modify the relation records between the post and updated tags
        foreach ($newTags as $tag) {
            Tag::firstOrCreate([
                'description' => $tag
            ]);
            PostTag::firstOrCreate([
                'tag_description' => $tag,
                'post_id' => $post->id
            ]);
        }
        //Remove the relation records between tags non existing in the new content and the updated post
        $removedTags = $postService->getRemovedTags($oldTags, $newTags);
        $post->tags()->detach($removedTags);
        return $this->generalResponse(new PostResource($post), "OK");
    }

    /**
     * @OA\Delete(
     * path="/post/{postId}",
     * summary="Delete post",
     * description="A blog delete his/her post",
     * operationId="deletepost",
     * tags={"Posts"},
     * security={ {"bearer": {} }},
     *  @OA\Parameter(
     *          name="postId",
     *          description="Id of the post to be deleted.",
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
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($postId)
    {
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post id should be numeric.", "422");
        }
        $post = Post::where('id', $postId)->first();
        if (empty($post)) {
            return $this->generalResponse("", "This post doesn't exist", "404");
        }

        $this->authorize('delete', $post);

        //Deleting the have tags relation records upon deleting the post
        $post->tags()->detach();
        $post->delete();
        return $this->generalResponse("", "ok");
    }

    /**
     * @OA\Get(
     * path="/post/{postId}",
     * summary="Get specific post",
     * description="A blog get post",
     * operationId="getapost",
     * tags={"Posts"},
     *   @OA\Parameter(
     *          name="postId",
     *          description="Id of the post to be retrieved.",
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
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId, Request $request)
    {
        //The regular expression describes a value where from start (^) to end ($) it matches one or more (+) digits [0-9].
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post id should be numeric.", "422");
        }
        $post = Post::where('id', $postId)->first();
        if ($post == null) {
            return $this->generalResponse("", "This post was not found", "404");
        }
        return $this->generalResponse(new PostResource($post), "ok");
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
     *          description="Id of the blog that'd create the post.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Post Request has different types depeneds on post type :
     *     in text type:description or title are required, at least one of them ,keep reading is optinal
     *     in image type: at least one uplaoded image,
     *     in chat type: chat_body is required, chat_title is optional
     *     in quote type: quote_text is required, quote_body is optinal
     *     in video type: video is required, url_videos are optinal
     *     in audio type: audio is required
     *     in link type: link is required
     *     is general: all fields can be given, to be general at least two different field of types should given",
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
        $blog = Blog::where('id', $request->blog_id)->first();
        $this->authorize('create', [Post::class, $blog]);

        $publishedAt = ($request->post_time == null && ($request->post_status == 'published' || $request->post_status == 'private')) ? now() : $request->post_time;
        $post = Post::create([
            'status' => $request->post_status,
            'published_at' => $publishedAt,
            'body' => $request->post_body,
            'type' => $request->post_type,
            'blog_id' => $request->blog_id
        ]);


        $postService = new PostService();
        $tags = $postService->extractTags($post->body);

        //Iterate through the tags array
        foreach ($tags as $tag) {
            //if the tag was never found then create a new one
            Tag::firstOrCreate(
                ['description' => $tag]
            );
            //if the tag was found or wasn't, create a relation recording this post with that tag
            PostTag::create([
                'tag_description' => $tag,
                'post_id' => $post->id
            ]);
        }

        return $this->generalResponse(new PostResource($post), "ok");
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
     * path="/posts/{blogId}/published",
     * summary="Get posts of blog which are published",
     * description="A blog get blog's posts",
     * operationId="getposts",
     * tags={"Posts"},
     * @OA\Parameter(
     *          name="blogId",
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
     *          @OA\Property(property="pagination",type="object",
     *              @OA\Property(property="total",type="int",example=17),
     *              @OA\Property(property="count",type="int",example=7),
     *              @OA\Property(property="per_page",type="int",example=10),
     *              @OA\Property(property="current_page",type="int",example=2),
     *              @OA\Property(property="total_pages",type="int",example=2),
     *              @OA\Property(property="first_page_url",type="boolean",example=false),
     *              @OA\Property(property="next_page_url",type="string",example=null),
     *              @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/posts/{blog_id}?page=1"),),
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
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"This blog id is not found"})
     *        )
     *     )
     * )
     */

    /**
     * Get all published posts of a specific blog.
     *
     * @param int $blogId The id of the blog whose published posts will be retrieved.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }

        $blog = Blog::where('id', $blogId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This blog id is not found", "404");
        }

        $publishedPosts = $blog->posts()->where('status', 'published')->paginate(Config::PAGINATION_LIMIT);

        return $this->generalResponse(new PostCollection($publishedPosts), "OK");
    }

     /**
     * @OA\Get(
     * path="/post/{blogId}/draft",
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
     *          @OA\Property(property="pagination",type="object",
     *              @OA\Property(property="total",type="int",example=17),
     *              @OA\Property(property="count",type="int",example=7),
     *              @OA\Property(property="per_page",type="int",example=10),
     *              @OA\Property(property="current_page",type="int",example=2),
     *              @OA\Property(property="total_pages",type="int",example=2),
     *              @OA\Property(property="first_page_url",type="boolean",example=false),
     *              @OA\Property(property="next_page_url",type="string",example=null),
     *              @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/posts/{blog_id}?page=1"),),
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
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"This blog id is not found."})
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"The blog Id should be numeric."})
     *        )
     *     )
     * )
     */

    /**
     * Get Draft Posts.
     * Retrieve all draft posts for any blog of the authenticated user blogs.
     *
     * @param int $blogId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDraftPosts($blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog Id should be numeric.", "422");
        }

        $blog = Blog::where('id', $blogId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This blog id is not found.", "404");
        }

        $this->authorize('viewDraftPosts', [Post::class, $blog]);

        $draftPosts = $blog->posts()->where('status', 'draft')->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse(new PostCollection($draftPosts), "OK");
    }

    /**
     * @OA\Put(
     * path="/posts/pin",
     * summary="Make a post is pinned in a blog",
     * description="A blog change the post to be pinned",
     * operationId="pinPost",
     * tags={"Posts"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *  required=true,
     *  description="
     *      blog_id: is the one of the logged in user blogs
     *      post_id: is the post id the logged in user wants to pin in that blog",
     *  @OA\JsonContent(
     *      @OA\Property(property="blog_id", type="int", example="12"),
     *      @OA\Property(property="post_id", type="int", example="6"),
     * )),
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
     * pin a post in this blog
     *
     * @param App\Http\Requests\BlogPostRequest $request
     * @return json
     **/
    public function pinPost(BlogPostRequest $request)
    {
        $request->validated();
        $isPinned = (new PostService())->pinPostService($request->blog_id, $request->post_id);
        if ($isPinned) {
            return $this->generalResponse("", "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

    /**
     * @OA\Put(
     * path="/posts/unpin",
     * summary="Make a post unpinned in a blog",
     * description="A blog change the post to be unpinned",
     * operationId="unpinPost",
     * tags={"Posts"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *  required=true,
     *  description="
     *      blog_id: is the one of the logged in user blogs
     *      post_id: is the post id the logged in user wants to unpin in that blog",
     *  @OA\JsonContent(
     *      @OA\Property(property="blog_id", type="int", example="12"),
     *      @OA\Property(property="post_id", type="int", example="6"),
     * )),
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
     * unpin a specific post in this blog
     *
     * @param App\Http\Requests\BlogPostRequest $request
     * @return json
     **/
    public function unpinPost(BlogPostRequest $request)
    {
        $request->validated();
        $isUnpinned = (new PostService())->unpinPostService($request->blog_id, $request->post_id);
        if ($isUnpinned) {
            return $this->generalResponse("", "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

     /**
     * @OA\Put(
     * path="/posts/change_status",
     * summary="change status of posts in a blog",
     * description="A blog delete his/her post",
     * operationId="changePostStatus",
     * tags={"Posts"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *  required=true,
     *  description="
     *      blog_id: is the one of the logged in user blogs
     *      post_id: is the post id the logged in user wants to change its status
     *      post_status: is one of three types published, private or draft",
     *  @OA\JsonContent(
     *      @OA\Property(property="blog_id", type="int", example="12"),
     *      @OA\Property(property="post_id", type="int", example="6"),
     *       @OA\Property(property="post_status", type="string", example="draft")
     * )),
     * @OA\RequestBody(
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
     * change post status to one of three types private, draft or published
     *
     * @param App\Http\Requests\BlogPostRequest $request
     * @return json
     **/
    public function changePostStatus(BlogPostRequest $request)
    {
        if (!$request->filled('post_status')) {
            return $this->errorResponse("the post_status field is required", 422);
        }
        $request->validated();
        $isChanged = (new PostService())->changePostStatusService($request->blog_id, $request->post_id, $request->post_status);
        if ($isChanged) {
            return $this->generalResponse("", "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

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
