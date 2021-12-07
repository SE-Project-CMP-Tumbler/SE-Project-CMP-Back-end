<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\FollowBlog;
use App\Services\BlogService;
use App\Http\Resources\CheckFollowBlogResource;
use Illuminate\Http\Request;

class FollowBlogController extends Controller
{

 /**
 * @OA\Post(
 * path="/follow_blog/{blog_id}",
 * summary="follow blog",
 * description=" Primary blog follow another blog",
 * operationId="followblog",
 * tags={"Follow Blogs"},
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
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found blog"})
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
  *follow  specific blog
  * @param \Request $request
  * @param  $blogId
  * @return \json
 */
    public function store(Request $request, $blogId)
    {
        if (preg_match('([0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $primaryBlog = $request->user()->blogs->where('is_primary', true)->first();
        if ($primaryBlog->id == $blogId) {
            return $this->generalResponse("", "You can't follow your self", "422");
        }
        $BlogService = new BlogService();
        $check = $BlogService->checkIsFollowed($primaryBlog->id, $blogId);
        if ($check) {
            return $this->generalResponse("", "You already follow this blog", "422");
        }
        FollowBlog::create(['follower_id' => $primaryBlog->id , 'followed_id' => $blogId]);
        return $this->generalResponse("", "200");
    }
/**
 * @OA\Delete(
 * path="/follow_blog/{blog_id}",
 * summary="unfollow blog",
 * description=" Primary blog unfollow another blog",
 * operationId="unfollowblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id of unfollowed blog ",
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
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 * )
 */
 /**
  *unfollow  specific blog
  * @param \Request $request
  * @param  $blogId
  * @return \json
 */
    public function delete(Request $request, $blogId)
    {
        if (preg_match('([0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $primaryBlog = $request->user()->blogs->where('is_primary', true)->first();
        $BlogService = new BlogService();
        if ($primaryBlog->id == $blogId) {
            return $this->generalResponse("", "You can't unfollow your self", "422");
        }
        $check = $BlogService->checkIsFollowed($primaryBlog->id, $blogId);
        if (!$check) {
            return $this->generalResponse("", "You already don't follow this blog", "422");
        }
         FollowBlog::where(['follower_id' => $primaryBlog->id , 'followed_id' => $blogId])->delete();
         return $this->generalResponse("", "200");
    }
/**
 * @OA\Get(
 * path="/followers/{blog_id}",
 * summary="follower's blog",
 * description=" Primary blog  get all his/her followers",
 * operationId="followersblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id of current blog  ",
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
 *        @OA\Property(property="followers", type="array",
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
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
 * path="/followings",
 * summary="followings's blog",
 * description=" Primary blog  get all his/her followings",
 * operationId="followingblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *        @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        @OA\Property(property="response", type="object",
 *        @OA\Property(property="followings", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                  )
 *              ),
 *    )
 *       )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
 * path="/followed_by/{blog_id}",
 * summary="followed_by blog",
 * description=" Check if I follow  another specific blog",
 * operationId="followingblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="The id of the blog to check if he/she is following the current blog",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *        @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *         @OA\Property(property="response", type="obeject",example={"followed":true},
 *
 *              ),
 *       )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
  * Check follow of blog
  * @param int $followedId
  *@param int $followerId
  * @return boolean
 */
    public function checkFollowed(Request $request, $blogId)
    {
        if (preg_match('([0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $primaryBlog = $request->user()->blogs->where('is_primary', true)->first();
        $BlogService = new BlogService();
        $check = $BlogService->checkIsFollowed($primaryBlog->id, $blogId);
      // check this line with TAs====>
        return $this->generalResponse(["followed" => $check], "ok");
    }
 /**
 * @OA\Get(
 * path="/total_followers/{blog_id}",
 * summary="total_followers number of blog",
 * description=" return total_followers number of blog",
 * operationId="followernumberblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="The id of current blog",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *        @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *         @OA\Property(property="response", type="obeject",example={"followers":5},
 *
 *              ),
 *       )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
 * path="/total_followings/{blog_id}",
 * summary="total_followings number of blog",
 * description=" return total_followings number of blog",
 * operationId="followingnumberblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="The id of current blog",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *        @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *         @OA\Property(property="response", type="obeject",example={"followings":5},
 *
 *              ),
 *       )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *   *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
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
