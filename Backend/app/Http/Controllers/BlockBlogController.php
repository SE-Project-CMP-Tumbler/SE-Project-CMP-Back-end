<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Http\Resources\BlogCollection;
use App\Models\Block;
use App\Models\Blog;
use App\Policies\BlockPolicy;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlockBlogController extends Controller
{

/**
 * @OA\Post(
 * path="/block/{blocker_id}/{blocked_id}",
 * summary="block blog",
 * description="One of the authenticated user's blogs block another blog",
 * operationId="blockBlog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *     name="blocker_id",
 *     description="The id of the blog that will do the block action.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 * @OA\Parameter(
 *     name="blocked_id",
 *     description="The id of the blog on which the block will be done.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 *
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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"One of the blog ids was not found"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))
 * ),
 *    @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
    /**
     * Create the block relation between 2 blogs.
     *
     * @param int $blockerId The id of the blog that will do the block action.
     * @param int $blockedId The id of the blog on which the block will be done.
     * @return \Illuminate\Http\JsonResponse
     */
    public function block($blockerId, $blockedId)
    {
        if (preg_match('(^[0-9]+$)', $blockerId) == false || preg_match('(^[0-9]+$)', $blockedId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }

        //Check the existence of these 2 blogs.
        $blockerBlog = Blog::where('id', $blockerId)->first();
        $blockedBlog = Blog::where('id', $blockedId)->first();

        if (empty($blockerBlog) || empty($blockedBlog)) {
            return $this->generalResponse("", "One of the blog ids was not found", "404");
        }

        //Check the authorization for this blog to do the block.
        $this->authorize('create', [Block::class, $blockerBlog]);

        //Check the validity of the blocking action
        //A block can't block a blog that shares the same user.
        if ($blockerBlog->user_id == $blockedBlog->user_id) {
            return $this->generalResponse("", "Forbidden, A block can't block a blog that shares the same user.", "403");
        }

        //Check if the block relation already exists
        $blogService = new BlogService();
        if ($blogService->checkIsBlocking($blockerId, $blockedId)) {
            return $this->generalResponse("", "You're already blocking this blog.", "422");
        }

        //Add the relation record that defines that the blocker has blocked the blog to be blocked.
        Block::create([
            'blocker_id' => $blockerBlog->id,
            'blocked_id' => $blockedBlog->id
        ]);
        return $this->generalResponse("", "ok", "200");
    }
/**
 * @OA\Delete(
 * path="/block/{blocker_id}/{blocked_id}",
 * summary="unblock blog",
 * description="One of the Authenticated user's blogs unblock another blog",
 * operationId="unblockBlog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *     name="blocker_id",
 *     description="The id of the blog that will do the block action.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 * @OA\Parameter(
 *     name="blocked_id",
 *     description="The id of the blog on which the block will be done.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *       )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description=" Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"One or Both blog ids were not found"})
 *        )
 *     ),
 *     @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
    public function unblock($blockerId, $blockedId)
    {
        if (preg_match('(^[0-9]+$)', $blockerId) == false || preg_match('(^[0-9]+$)', $blockedId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }

        $blogService = new BlogService();
        $blockerBlog = $blogService->findBlog($blockerId);
        $blockedBlog = $blogService->findBlog($blockedId);
        if (empty($blockerBlog) || empty($blockedBlog)) {
            return $this->generalResponse("", "One or Both blog ids were not found", "404");
        }

        if ($blockedBlog->user_id == $blockerBlog->user_id) {
            return $this->generalResponse("", "Forbidden, A block can't unblock a blog that shares the same user.", "403");
        }

        $this->authorize('delete', [Block::class, $blockerBlog]);

        $isBlocking = $blogService->checkIsBlocking($blockerId, $blockedId);
        if (!$isBlocking) {
            return $this->generalResponse("", "Can't unblock a not blocked blog", "422");
        }

        $blockerBlog->blockings()->detach($blockedId);
        return $this->generalResponse("", "ok", "200");
    }
/**
 * @OA\Get(
 * path="/block/{blog_id}",
 * summary="blocked blogs",
 * description="Get all blogs blocked by the blog_id",
 * operationId="getBlockedBlogs",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="The Blog id for which his/her blocked blogs list will be retrieved.",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *       @OA\Property(property="blocked blog", type="array",
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
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"The blog was not found"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))
 * ),
 *     @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
    /**
     * Get all blogs blocked by the blogId
     *
     * @param int $blogId The Blog id for which his/her blocked blogs list will be retrieved.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBlockings($blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }

        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        //Check the existence of the blog.
        if (empty($blog)) {
            return $this->generalResponse("", "The blog was not found", "404");
        }
        //Check the authorization of the user to get a blog's blocking list.
        $this->authorize('view', [Block::class, $blog]);

        $blockings = $blog->blockings()->paginate(Config::API_PAGINATION_LIMIT);
        return $this->generalResponse(new BlogCollection($blockings), "ok");
    }
/**
 * @OA\Get(
 * path="/block/{blocker_id}/{blocked_id}",
 * summary="Check if one blog blocks another.",
 * description="Check whether the blocker blog id is blocking the blocked blog id or not.",
 * operationId="blockBlog",
 * tags={"Block Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *     name="blocker_id",
 *     description="The id of the blog that may have done the block action.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 * @OA\Parameter(
 *     name="blocked_id",
 *     description="The id of the blog on which the block may have been done.",
 *     required=true,
 *     in="path",
 *     @OA\Schema(
 *         type="integer")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *       @OA\Property(property="is_blocking", type="bool", example=false),))),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"One of the blog ids was not found"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))
 * ),
 *     @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
    /**
     * Check if one blog blocks another.
     * Check whether the blocker blog id is blocking the blocked blog id or not.
     *
     * @param int $blockerId The id of the blog that may have done the block action.
     * @param int $blockedId The id of the blog on which the block may have been done.
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIsBlocking($blockerId, $blockedId)
    {
        if (preg_match('(^[0-9]+$)', $blockerId) == false || preg_match('(^[0-9]+$)', $blockedId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }

        //Check the existence of these 2 blogs.
        $blockerBlog = Blog::where('id', $blockerId)->first();
        $blockedBlog = Blog::where('id', $blockedId)->first();

        if (empty($blockerBlog) || empty($blockedBlog)) {
            return $this->generalResponse("", "One of the blog ids was not found", "404");
        }

        //Check the authorization of this user to check the blocking status between 2 blogs.
        $this->authorize('view', [Block::class, $blockerBlog]);

        $blogService = new BlogService();
        $isBlocking = $blogService->checkIsBlocking($blockerId, $blockedId);
        return $this->generalResponse(['is_blocking' => $isBlocking], "ok", "200");
    }

}
