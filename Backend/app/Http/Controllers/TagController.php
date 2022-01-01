<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Http\Requests\TagRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Blog;
use App\Models\BlogFollowTag;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
/**
 * @OA\Get(
 * path="/tag/is_following/{tag_description}",
 * summary="Check if primary blog follows a tag.",
 * description="Check whether the primary blog is following a specific tag or not.",
 * operationId="checkFollowTag",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *     name="tag_description",
 *     description="The name of the tag to check if the primary blog follows or not.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="string")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *          @OA\Property(property="is_following", type="bool", example=false),))),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))
 * ),
 * )
 */
    /**
     * Check whether the primary blog follows a specific tag.
     *
     * @param string $tagDescription tag to check if the primary blog follows
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIsFollowing($tagDescription)
    {
        $blogService = new BlogService();

        $isFollowing = $blogService->checkIsFollowingTag($tagDescription);
        return $this->generalResponse(['is_following' => $isFollowing], "ok");
    }
/**
 * @OA\Post(
 * path="/tag/data/{post_id}/{tag_description}",
 * summary="Create a new tag",
 * description="Creates a specific tag",
 * operationId="createTag",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * @OA\Parameter(
 *          name="post_id",
 *          description="The Id of the post which is creating the tag inside it",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),),),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))),
 *  @OA\Response(
 *    response=409,
 *    description="Conflict",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "409", "msg":"The request could not be completed due to a conflict with the current state of the resource."})))
 * )
 */
    /**
     * Creates a new tag
     *
     * @param \App\Http\Requests\TagRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TagRequest $request)
    {
        if (Tag::where('description', $request->tag_description)->count() > 0) {
            return $this->generalResponse("", "this tag already exists", "422");
        }

        $post = Post::where('id', $request->post_id)->first();
        if ($post == null) {
            return $this->generalResponse("", "this post doesn't exist", "404");
        }

        $this->authorize('create', [Tag::class, $post]);
        Tag::create([
            'description' => $request->tag_description
        ]);

        PostTag::create([
            'post_id' => $request->post_id,
            'tag_description' => $request->tag_description
        ]);

        return $this->generalResponse("", "ok");
    }
/**
 * @OA\Get(
 * path="/tag/data/{tag_description}",
 * summary="Get data of a specific tag",
 * description="Returns data of a specific tag",
 * operationId="getTagData",
 * security={ {"bearer": {} }},
 * tags={"Tags"},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="tag_description",type="string",example="books"),
 *          @OA\Property(property="tag_image",type="string", format="byte", example=""),
 *          @OA\Property(property="followed",type="bool", example=false),
 *          @OA\Property(property="followers_number",type="integer", example=1026),
 *          @OA\Property(property="posts_count",type="integer", example=16),)),),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified description was not found"}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Get information of a tag
     *
     * @param string $tag_description
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tagDescription)
    {
        $tag = Tag::withCount('posts')
            ->where('description', $tagDescription)
            ->first();

        if (empty($tag)) {
            return $this->generalResponse("", "the tag doesn't exist", 404);
        }
        return $this->generalResponse(new TagResource($tag), "ok");
    }
/**
 * @OA\Get(
 * path="/tag/posts/{tag_description}?sort=sort_type",
 * summary="Get all posts associated with tag",
 * description="Returns list of all posts associated to a specific tag",
 * operationId="getTagPosts",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 * @OA\Parameter(
 *          name="sort",
 *          description="The sort method to retrieve the posts by. Allowed values for sort_type are:
 *          recent: the posts are retrieved sorted by the most recently published,
 *          top: the posts are retrieved sorted descendingly by the number of engagments and notes they got.
 * By default if no sort_type is specified, posts will be retrieved by the most recent order.
 * If sort_type took any value other than recent or top, be default the posts will be retrieved by the most recent order",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string")),
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/tag/posts/books?sort=top"),),
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="post_body", type="string", example="<p>This is so frustrating. #books #reading</p>"),
 *                  @OA\Property(property="post_type", type="string", example="text"),
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="blog_username", type="string", example=""),
 *                  @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                  @OA\Property(property="blog_avatar_shape", type="string", example="square"),
 *                  @OA\Property(property="blog_title", type="string", example="Untitled"),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2021"),),),)),),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Tag description provided was not found."}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Retrieve the posts having a specific tag within them.
     * The posts retrieval is either by the most recent or by the top engaging post.
     *
     * @var string $tagDescription
     * @var string $sort_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagPosts(string $tagDescription, Request $request)
    {
        //check the existence of the tag
        $tag = Tag::where('description', $tagDescription)->first();
        if (empty($tag)) {
            return $this->generalResponse("", "Tag description provided was not found.", "404");
        }
        //direct to the corresponding sort method
        switch ($request->sort) {
            case 'top':
                # return the tag's posts sorted by the most engaging
                $posts = $tag->posts()
                        ->withCount(['postLikers'])
                        ->where('status', 'published')
                        ->orderBy('post_likers_count', 'desc')
                        ->paginate(Config::PAGINATION_LIMIT);
                return $this->generalResponse(new PostCollection($posts), "OK");
                break;

            default:
                # return the tag's posts sorted by the most recently published
                $posts = $tag->posts()
                    ->where('status', 'published')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Config::PAGINATION_LIMIT);
                return $this->generalResponse(new PostCollection($posts), "OK");
        }
    }
/**
 * @OA\Get(
 * path="/tag/trending",
 * summary="Get all tags which are trending",
 * description="Returns list of tags which are trending. The trending metric used is the count of posts this tag was mentioned inside.",
 * operationId="gettrendingTags",
 * tags={"Tags"},
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *       @OA\Property(property="response",type="object",
 *              @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=120),
 *                  @OA\Property(property="count",type="int",example=10),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=12),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="last_page_url",type="int",example=12),
 *                  @OA\Property(property="next_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=3"),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=1"),),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example=""),
 *                  @OA\Property(property="posts_count",type="int",example=12),
 *                  @OA\Property(property="followed",type="bool", example=false),
 *                  @OA\Property(property="followers_number",type="integer", example=1026),),),),),),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Get tags ordered descendingly by their post counts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $trending = Tag::withCount(['posts'])
            ->orderBy('posts_count', 'desc')
            ->paginate(Config::PAGINATION_LIMIT);

        return $this->generalResponse(new TagCollection($trending), "ok");
    }
/**
 * @OA\Get(
 * path="/tag/suggesting",
 * summary="Get suggestions for tags to follow",
 * description="Returns list of tags which are trending but the primary blog doesn't follow. The trending metric used is the count of posts this tag was mentioned inside.",
 * operationId="getSuggestionTags",
 * security={ {"bearer": {} }},
 * tags={"Tags"},
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *       @OA\Property(property="response",type="object",
 *              @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=120),
 *                  @OA\Property(property="count",type="int",example=10),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=12),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="last_page_url",type="int",example=12),
 *                  @OA\Property(property="next_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=3"),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=1"),),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example=""),
 *                  @OA\Property(property="posts_count",type="int",example=12),
 *                  @OA\Property(property="followed",type="bool", example=false),
 *                  @OA\Property(property="followers_number",type="integer", example=1026),),),),),),
 *
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Get tags ordered descendingly by their post counts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions()
    {
        $blogService = new BlogService();
        $primaryBlog = $blogService->getPrimaryBlog(auth()->user());

        $followingTags = BlogFollowTag::where('blog_id', $primaryBlog->id)->get(['tag_description']);

        $suggestings = Tag::whereNotIn('description', $followingTags)
            ->withCount(['posts'])
            ->orderBy('posts_count', 'desc')
            ->paginate(Config::PAGINATION_LIMIT);

        return $this->generalResponse(new TagCollection($suggestings), "ok");
    }
}
