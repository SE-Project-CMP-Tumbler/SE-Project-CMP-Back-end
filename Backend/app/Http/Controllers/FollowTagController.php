<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Http\Resources\TagCollection;
use App\Models\BlogFollowTag;
use App\Models\Tag;
use App\Services\BlogService;
use Illuminate\Http\Request;

class FollowTagController extends Controller
{
/**
 * @OA\Post(
 * path="/follow_tag/{tag_description}",
 * summary="Follows a specific tag",
 * description="Add a new follow relation between the blog and the tag",
 * operationId="followTag",
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
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),),),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified id was not found"}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Create the follow relation record.
     * The follow relation is between the current authenticated primary blog and the tag description given.
     *
     * @param string $tagDescription
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($tagDescription)
    {
        $user = auth()->user();
        $primaryBlog = $user->blogs()->where('is_primary', true)->first();

        $tag = Tag::where([
            'description' => $tagDescription
        ])->first();

        if (empty($tag)) {
            return $this->generalResponse("", "A Tag with the specified id was not found", "404");
        }

        $blogFollowTag = BlogFollowTag::where([
            'blog_id' => $primaryBlog->id,
            'tag_description' => $tagDescription
        ])->first();

        if (!empty($blogFollowTag)) {
            return $this->generalResponse("", "The blog already follows this tag!", "422");
        }

        BlogFollowTag::create([
            'blog_id' => $primaryBlog->id,
            'tag_description' => $tagDescription
        ]);
        return $this->generalResponse("", "OK", "200");
    }
/**
 * @OA\Delete(
 * path="/follow_tag/{tag_description}",
 * summary="Unfollows a specific tag",
 * description="Remove the follow relation between the blog and the tag",
 * operationId="unfollowTag",
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
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}))),
 *
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified id was not found"}))),
 *
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 *
 *  @OA\Response(
 *    response=500,
 *    description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"error"})))
 * )
 */
    /**
     * Unfollow a tag.
     * Remove the follow relation between the currenlty authenticated primary blog and a specific tag.
     *
     * @param string $tagDescription
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($tagDescription)
    {
        $user = auth()->user();
        $primaryBlog = $user->blogs()->where('is_primary', true)->first();

        $tag = Tag::where('description', $tagDescription)->first();
        if (empty($tag)) {
            return $this->generalResponse("", "A Tag with the specified id was not found", "404");
        }

        $followRelation = BlogFollowTag::where('tag_description', $tagDescription)
            ->where('blog_id', $primaryBlog->id)->first();

        if (empty($followRelation)) {
            return $this->generalResponse("", "The Blog isn't already following this tag!", "422");
        }

        $primaryBlog->tags()->detach($tagDescription);
        return $this->generalResponse("", "OK", "200");
    }
/**
 * @OA\Get(
 * path="/follow_tag",
 * summary="Get all tags the primary blog follows",
 * description="Returns list of all tags the primary blog follow",
 * operationId="getfollowingTags",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
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
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))),
 *
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
    /**
     * Get all tags the primary blog follows.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagsFollowed()
    {
        $blogService = new BlogService();
        $primaryBlog = $blogService->getPrimaryBlog(auth()->user());
        $followedTags = $primaryBlog->tags()->paginate(Config::API_PAGINATION_LIMIT);
        return $this->generalResponse(new TagCollection($followedTags), "OK", "200");
    }
}
