<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Blog;
use App\Models\Reply;
use App\Models\Like;
use App\Models\ReplyMentionBlog;
use App\Http\Resources\ReplyResource;
use App\Services\PostService;
use App\Notifications\ReplyNotification;
use App\Notifications\LikeNotification;

class PostActionController extends Controller
{
 /**
 * @OA\Post(
 * path="/post/reply/{post_id}",
 * summary="add reply on a post",
 * description="send note/reply on a specific post on any blog",
 * operationId="addPostNote",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to reply on",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="intger",
 *    )
 *  ),
 *  @OA\RequestBody(
 *   description="reply_text: reply text body",
 *   @OA\JsonContent(
 *       @OA\Property(property="reply_text", type="string", example="Good Work"),
 *     ),
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Reply",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *        @OA\Property(property="blog_object", type="array",
 *          @OA\Items(
 *            @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *            @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *            @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *            @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *            @OA\Property(property="blog_id", type="integer", example=1032),
 *            @OA\Property(property="followed", type="boolean", example=false),
 *            @OA\Property(property="reply_id", type="integer", example=5),
 *             @OA\Property(property="reply_time", type="date-time", example="02-02-2012"),
 *              @OA\Property(property="reply_text", type="string", example="What an amazing post!"),
 *          )
 *       ),
 *    ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 *   @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))
 * ),
 *   @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 * )
 */

    /**
     * add a reply on a post
     *
     * @param int $postId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReply($postId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post Id should be numeric.", "422");
        }

        $post = Post::where('id', $postId)->first();
        if (empty($post)) {
            return $this->generalResponse("", "This post id is not found.", "404");
        }
        // the blog made the reply
        $actorBlog = Blog::where([['user_id',$request->user()->id],['is_primary', true]])->first();
        $actorBlogID = ($actorBlog) ['id'];
        $this->authorize('canReply', $post);
        $reply = Reply::create([
            'post_id' => $postId,
            'blog_id' => $actorBlogID,
            'description' => $request->reply_text
        ]);
        $mentionedBlogsUsername = (new PostService())->extractMentionedBlogs($reply->description);
        foreach ($mentionedBlogsUsername as $blogUsername) {
            $blog = Blog::where('username', $blogUsername)->first();
            if ($blog && (ReplyMentionBlog::where([['reply_id',$reply->id],['blog_id',$blog['id']]])->count() == 0)) {
                ReplyMentionBlog::create([
                'reply_id' => $reply->id,
                'blog_id' => $blog['id'],
                ]);
            }
        }

        // add the notificaions for the reply on post - don't notify your self
        $recipientBlog = $post->blog()->first();
        if ($actorBlog->id !== $recipientBlog->id) {
            $notifedUser = $recipientBlog->user()->first();
            $notifedUser->notify(new ReplyNotification($actorBlog, $recipientBlog, $post, $reply));
        }
        return $this->generalResponse(["blog_object" => new ReplyResource($reply)], "ok");
    }

 /**
 * @OA\Post(
 * path="/post/like/{post_id}",
 * summary="add like on a post",
 * description="send like on a specific post on any blog via this api route",
 * operationId="addPostLike",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to reply on",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="int",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
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
     * add a like on a post
     *
     * @param int $postId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLike($postId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post Id should be numeric.", "422");
        }

        $post = Post::where('id', $postId)->first();
        if (empty($post)) {
            return $this->generalResponse("", "This post id is not found.", "404");
        }

        // the blog made the like
        $actorBlog = Blog::where([['user_id',$request->user()->id],['is_primary', true]])->first();
        $actorBlogID = ($actorBlog) ['id'];
        $this->authorize('canLike', $post);
        Like::create([
            'post_id' => $postId,
            'blog_id' => $actorBlogID
        ]);

        // add the notificaions for the like on post - don't notify your self
        $recipientBlog = $post->blog()->first();
        if ($actorBlog->id !== $recipientBlog->id) {
            $notifedUser = $recipientBlog->user()->first();
            $notifedUser->notify(new LikeNotification($actorBlog, $recipientBlog, $post));
        }

        return $this->generalResponse("", "ok");
    }
 /**
 * @OA\Get(
 * path="/post/like/{blog_id}/{post_id}",
 * description="check if specific blog likes specific post",
 * operationId="checkBlogLikesPost",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *   in="path",
 *   name="blog_id",
 *   description="blog id",
 *   required=true,
 *   example="14253976254",
 *    @OA\Schema(
 *       type="intger",
 *    )
 * ),
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="post id",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="integer",
 *    )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
 *       @OA\Property(property="response",type="object",
 *         @OA\Property(property="like_status", type="boolean", example=false),
 *       ),
 *   ),
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
     * check if a blog likes a post
     *
     * @param int $blogId
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkLiked($blogId, $postId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog Id should be numeric.", "422");
        }

        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post Id should be numeric.", "422");
        }

        $blog = Blog::where('id', $blogId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This blog id is not found.", "404");
        }

        $post = Post::where('id', $postId)->first();
        if (empty($post)) {
            return $this->generalResponse("", "This post id is not found.", "404");
        }

        $like = Like::where([['blog_id', $blogId] , ['post_id', $postId]])->first();
        if (empty($like)) {
            return $this->generalResponse(["like_status" => false], "ok", "200");
        }
        return $this->generalResponse(["like_status" => true], "ok", "200");
    }
 /**
 * @OA\Delete(
 * path="/post/reply/{reply_id}",
 * summary="delete a specific note/repy from a post",
 * description="each blog can delete any note/repy from their posts, using this api route",
 * operationId="deletePostNote",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="reply_id",
 *    description="for the reply to be deleted",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="int",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Post Reply Deletion",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
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
 *  @OA\Response(
 *   response=404,
 *   description="Not Found",
 *   @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
 *   ),
 *  ),
 * )
 */
    /**
     * delete a reply on a post
     *
     * @param int $reply_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReply($reply_id)
    {
        if (preg_match('(^[0-9]+$)', $reply_id) == false) {
            return $this->generalResponse("", "The reply Id should be numeric.", "422");
        }

        $reply = Reply::where('id', $reply_id)->first();
        if (empty($reply)) {
            return $this->generalResponse("", "This reply id is not found.", "404");
        }
        $this->authorize('canDeleteReply', [Post::class, $reply]);
        //delete
        $reply->delete();
        return $this->generalResponse("", "ok", "200");
    }

 /**
 * @OA\Delete(
 * path="/post/like/{post_id}",
 * description="delete a specific like from a post",
 * operationId="deletePostLike",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *    in="path",
 *    name="post_id",
 *    description="for the post to delete that like from",
 *    required=true,
 *    example="666746885177049088",
 *    @OA\Schema(
 *       type="integer",
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful Like Deletion",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *    ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     ),
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 *  @OA\Response(
 *   response=404,
 *   description="Not Found",
 *   @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
 *   ),
 *  )
 * )
 */
    /**
     * delete a like on a post
     *
     * @param int $postId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLike($postId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post Id should be numeric.", "422");
        }

        $blog = Post::where('id', $postId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This post id is not found.", "404");
        }

        $blogId = (Blog::where([['user_id',$request->user()->id],['is_primary', true]])->first()) ['id'];
        $like = Like::where([['blog_id', $blogId] , ['post_id', $postId]])->first();
        if (empty($like)) {
            return $this->generalResponse("", "not found", "404");
        }

        $this->authorize('canDeleteLike', [Post::class, $like]);
        Like::where([['blog_id', $blogId] , ['post_id', $postId]])->delete();
        return $this->generalResponse("", "ok", "200");
    }
}
