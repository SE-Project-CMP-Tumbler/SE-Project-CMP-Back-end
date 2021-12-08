<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
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
 *          @OA\Property(property="followers_number",type="integer", example=1026),)),),
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
    public function show($tag_description)
    {
        $tag = Tag::where('description', $tag_description)->first();

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
 *          description="The sort method to retrieve the posts",
 *          required=false,
 *          in="query",
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
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *                  @OA\Property(property="post_tags", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="books"),
 *                          @OA\Property(property="1", type="string", example="reading"),
 *                          @OA\Property(property="2", type="string", example="stay positive"),)),),),)),),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Tag description or sort type was not found"}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
/**
 * @OA\Get(
 * path="/tag/trending",
 * summary="Get all tags which are trending",
 * description="Returns list of  tags  which are trending",
 * operationId="gettrendingTags",
 * tags={"Tags"},
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *       @OA\Property(property="response",type="object",
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example="")))))),
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
        $trending = DB::table('tags')
        ->join('post_tag', 'tags.description', '=', 'post_tag.tag_description')
        ->join('posts', 'post_tag.post_id', '=', 'posts.id')
        ->select(DB::raw('count(description) as num_of_posts, description, image'))
        ->groupBy('tags.description')
        ->orderBy('num_of_posts', 'desc')
        ->get();

        return $this->generalResponse(new TagCollection($trending), "ok");
    }
}
