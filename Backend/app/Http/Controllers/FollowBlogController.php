<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\FollowBlog;
use App\Http\Misc\Helpers\Config;
use App\Notifications\UserFollowedNotification;
use App\Services\BlogService;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\FollowBlogCollection;
use App\Http\Resources\FollowingBlogCollection;
use App\Http\Resources\FollowingOfBlogCollection ;
use App\Http\Resources\CheckFollowBlogResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BlogFollowRequest;

class FollowBlogController extends Controller
{
 /**
 * @OA\GET(
 * path="/search_follow_blog/{blog_username}",
 * summary="follow blog",
 * description=" Primary blog search about blog is in followers or not",
 * operationId="followblogby username",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog username",
 *          description=" ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"X follows you!"}),
 *        )
 *     ),
 *   @OA\Response(
 *    response=422,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"X does not follow you"})
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
  *follow  specific blog by username
  * @param \Request $request
  * @param  $blogUsername
  * @return \json
 */
    public function searchFollowBlog(Request $request, $blogUsername)
    {
        $blogService = new BlogService();
        $blog = $blogService->findBlogByUsername($blogUsername);
        if ($blog == null) {
            return $this->generalResponse("", "This blog username is not found", "404");
        }
        $primaryBlog =  $blogService->getPrimaryBlog($request->user());
        if ($primaryBlog->id == $blog->id) {
            return $this->generalResponse("", "You can not follow your self", "422");
        }
        $check = $primaryBlog->followers()->where('username', $blogUsername)->first();
        if ($check == null) {
            return $this->generalResponse("", $blogUsername . " does not follow you.", "422");
        }
        return $this->generalResponse("", $blogUsername . " follows you!");
    }
    /**
 * @OA\POST(
 * path="follow_blog_search",
 * summary="follow blog",
 * description=" Primary blog search about blog  and follow it",
 * operationId="followblogby username search",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description=  " The user can send blog username or  User email to follow another blog
 *    blog_value : blog_username or blog_email",
 *    @OA\JsonContent(
 *      required={"blog_value"},
 *      @OA\Property(property="blog_value", type="string", example="CairoBlogs"),
 *                )
 *               ),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"X follows you!"}),
 *        )
 *     ),
 *   @OA\Response(
 *    response=422,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"The input is required"})
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
  *follow  specific blog by search about username or email
  * @param \BlogFollowRequest $request
  * @return \json
 */
    public function followBlog(BlogFollowRequest $request)
    {
        $blogService = new BlogService();
        $user = User::where('email', $request->blog_value)->first();

        if ($user == null) {
            $blog = Blog::where('username', $request->blog_value)->first();
            if ($blog == null) {
                return $this->generalResponse("", "The blog is not found", "404");
            }
        } else {
            $blog = $blogService->getPrimaryBlog($user);
        }
        $primaryBlog =  $blogService->getPrimaryBlog($request->user());
        if ($primaryBlog->id == $blog->id) {
            return $this->generalResponse("", "You can not follow your self", "422");
        }
        $check = $blogService->checkIsFollowed($primaryBlog->id, $blog->id);
        if ($check) {
            return $this->generalResponse("", "You already follow this blog", "422");
        }
        $blogService->creatFollowBlog($primaryBlog->id, $blog->id);
        return $this->generalResponse("", "ok");
    }
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
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $followerUser = $request->user();
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $primaryBlog =  $blogService->getPrimaryBlog($followerUser);
        if ($primaryBlog->id == $blogId) {
            return $this->generalResponse("", "You can't follow your self", "422");
        }
        $check = $blogService->checkIsFollowed($primaryBlog->id, $blogId);
        if ($check) {
            return $this->generalResponse("", "You already follow this blog", "422");
        }
        $blogService->creatFollowBlog($primaryBlog->id, $blogId);

        // add the notifications
        $followedBlog = Blog::where('id', $blogId)->first();
        $followedUser = $followedBlog->user()->first();
        $followedUser->notify(new UserFollowedNotification($followerUser, $followedUser, $followedBlog));
        return $this->generalResponse("", "ok");
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
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $primaryBlog = $blogService->getPrimaryBlog($request->user());
        if ($primaryBlog->id == $blogId) {
            return $this->generalResponse("", "You can't unfollow your self", "422");
        }
        $check = $blogService->checkIsFollowed($primaryBlog->id, $blogId);
        if (!$check) {
            return $this->generalResponse("", "You already don't follow this blog", "422");
        }
        $blogService->deleteFollowBlog($primaryBlog->id, $blogId);
         return $this->generalResponse("", "ok");
    }
/**
 * @OA\Get(
 * path="/followers",
 * summary="follower's blog",
 * description=" Primary blog  get all his/her followers",
 * operationId="followersblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *          @OA\Property(property="pagination",type="object",example={"total": 1,"count": 1,"per_page": 10, "current_page": 1,"total_pages": 1,"first_page_url": true,
 *            "last_page_url": 1,
 *           "next_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=3",
 *           "prev_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=1"}),
 *          @OA\Property(property="followers", type="array",
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
 * get total number of get folllowings of primary blog of user
  * @param \Request $request
  * @param $blogId
  * @return \json
  */
    public function getFollowers(Request $request)
    {
        $blogService = new BlogService();
        $blog = $blogService->getPrimaryBlog($request->user());
        $followers = $blog->followers()->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse(new FollowBlogCollection($followers), "ok");
    }

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
 *         @OA\Property(property="pagination",type="object",example={"total": 1,"count": 1,"per_page": 10, "current_page": 1,"total_pages": 1,"first_page_url": true,
 *            "last_page_url": 1,
 *           "next_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=3",
 *           "prev_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=1"}),
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
 * get total number of get folllowings of primary blog of user
  * @param \Request $request
  * @param $blogId
  * @return \json
  */
    public function getFollowings(Request $request)
    {
        $blogService = new BlogService();
        $blog = $blogService->getPrimaryBlog($request->user());
        $followings = $blog->followings()->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse(new FollowingBlogCollection($followings), "200");
    }
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
    * @param \Request $request
  * @param int $followedId
  *@param int $followerId
  * @return boolean
 */
    public function checkFollowed(Request $request, $blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $primaryBlog = $blogService->getPrimaryBlog($request->user());
        $check = $blogService->checkIsFollowed($primaryBlog->id, $blogId);
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
 * get total number of get total number of folllowers
  * @param \Request $request
  * @param $blogId
  * @return \json
  */
    public function getTotalFollowers(Request $request, $blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $this->authorize('view', $blog);
        $followers = $blog->followers()->count();
        return $this->generalResponse(["followers" => $followers], "ok");
    }
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
 /**
 * get total number of get total number of folllowings
  * @param \Request $request
  * @param $blogId
  * @return \json
  */
    public function getTotalFollowings(Request $request, $blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $this->authorize('view', $blog);
        $followings = $blog->followings()->count();
        return $this->generalResponse(["followings" => $followings], "ok");
    }
    /**
 * @OA\Get(
 * path="/followings/{blog_id}",
 * summary="followings's blog",
 * description=" get followings of blog",
 * operationId="following specificblog",
 * tags={"Follow Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="blog_id",
 *          description="The id of the blog to check if he/she is following the current blog",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *        @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        @OA\Property(property="response", type="object",
 *         @OA\Property(property="pagination",type="object",example={"total": 1,"count": 1,"per_page": 10, "current_page": 1,"total_pages": 1,"first_page_url": true,
 *            "last_page_url": 1,
 *           "next_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=3",
 *           "prev_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=1"}),
 *        @OA\Property(property="followings", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="is_followed", type="boolean", example=true),
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
 * get  another followings of another blog
  * @param \Request $request
  * @param $blogId
  * @return \json
  */
    public function getanotherFollowings(Request $request, $blogId)
    {
        $blogService = new BlogService();
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $this->authorize('shareFollowings', $blog);
        $followings = $blog->followings()->paginate(Config::PAGINATION_LIMIT);
        $primaryBlog = $blogService->getPrimaryBlog($request->user());
        return $this->generalResponse(new FollowingOfBlogCollection($followings), "200");
    }
}
