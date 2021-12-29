<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\GraphRequest;
use App\Services\BlogService;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * @OA\Get(
     * path="/graph/notes/{blog_id}",
     * summary="get the notes",
     * description="get the notes for the activity graph",
     * tags={"Activity"},
     * operationId="getNotesActivityGraph",
     * security={ {"bearer": {} }},
     *  @OA\Parameter(
     *     name="blog_id",
     *     description="the blog id which this graph data belongs to",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer")),
     * @OA\Response(
     *    response=200,
     *    description="Successful response",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *       @OA\Property(property="response",type="object",
     *       @OA\Property(property="notes_count", type="integer", example=16),
     *       @OA\Property(property="new_followers_count", type="integer", example=326),
     *       @OA\Property(property="total_followers_count", type="integer", example=326),
     *       @OA\Property(property="data", type="object",
     *           example={
     *              {
     *               "timestamp": "2021-11-03 01:13:39",
     *               "notes": 5,
     *               "post_id": 51,
     *             },
     *             {
     *               "timestamp": "2021-17-03 01:13:39",
     *               "notes": 51,
     *               "post_id": 81,
     *             },
     *             {
     *               "timestamp": "2021-19-03 01:13:39",
     *               "notes": 9,
     *               "post_id": 123,
     *             },
     *           }),
     *    ),
     *   ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=404,
     *    description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=500,
     *    description="Internal Server error",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
     *        )
     *     )
     * ),
     *
     */

    /**
    * get the new notes data for a blog for drawing the graph
    *
    * @param \GraphRequest $request
    * @return \json
    */
    public function getNotesGraphData(GraphRequest $request)
    {
        $request->validated();
        $blog = Blog::where('id', $request->blog_id)->first();

        // the blog id in the replies table referes to the blog made the reply
        // need to relate each post with its blog id from the posts table
        $data = DB::select('select t2.post_id, t2.created_at::date as timestamp, count(*) as notes from 
            (select t1.post_id, t1.created_at from 
                (select replies.post_id, replies.created_at from replies 
                union ( select likes.post_id, likes.created_at from likes)) as t1, posts 
                where t1.post_id = posts.id and posts.blog_id = ' . $request->blog_id . ') as t2 
            group by date(created_at), post_id order by created_at::date;');

        $followers = $blog->followers()->count();
        $replies = $blog->replies()->count();

        $res = [
            "data" => $data,
            "notes_count" => $replies,
            "new_followers_count" => $followers,
            "total_followers_count" => $followers,
        ];

        return $this->generalResponse($res, "ok", 200);
    }

    /**
     * @OA\Get(
     * path="/graph/new_followers/{blog_id}",
     * summary="get the number of the new followers",
     * description="get the number of the new followers for the activity graph",
     * tags={"Activity"},
     * operationId="getNewFollwersActivityGraph",
     * security={ {"bearer": {} }},
     *  @OA\Parameter(
     *     name="blog_id",
     *     description="the blog id which this graph data belongs to",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer")),
     * @OA\Response(
     *    response=200,
     *    description="Successful response",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *       @OA\Property(property="response",type="object",
     *       @OA\Property(property="notes_count", type="integer", example=16),
     *       @OA\Property(property="new_followers_count", type="integer", example=326),
     *       @OA\Property(property="total_followers_count", type="integer", example=326),
     *       @OA\Property(property="data", type="object",
     *           example={
     *              {
     *               "timestamp": "2021-11-03 01:13:39",
     *               "new_followers": 5,
     *             },
     *             {
     *               "timestamp": "2021-17-03 01:13:39",
     *               "new_followers": 51,
     *             },
     *             {
     *               "timestamp": "2021-19-03 01:13:39",
     *               "new_followers": 9,
     *             },
     *           }),
     *    ),
     *   ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=404,
     *    description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=500,
     *    description="Internal Server error",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
     *        )
     *     )
     * ),
     *
     */
    /**
    * get the new followers for a blog
    *
    * @param \Request $request
    * @return \json
    */
    public function getNewFollwersGraphData(GraphRequest $request)
    {
        $request->validated();
        $data = DB::select('select count(*) as new_followers, created_at::date as timestamp 
            from follow_blog where followed_id = '
            . $request->blog_id .
            ' group by date(created_at) order by created_at::date;');

        $blog = Blog::where('id', $request->blog_id)->first();
        $followers = $blog->followers()->count();
        $replies = $blog->replies()->count();

        $res = [
            "data" => $data,
            "notes_count" => $replies,
            "new_followers_count" => $followers,
            "total_followers_count" => $followers,
        ];

        return $this->generalResponse($res, "ok", 200);
    }

    /**
     * @OA\Get(
     * path="/graph/total_followers/{blog_id}",
     * summary="get the total number of followers",
     * description="get the total number of followers for the activity graph",
     * tags={"Activity"},
     * operationId="getTotalFollwersActivityGraph",
     * security={ {"bearer": {} }},
     *  @OA\Parameter(
     *     name="blog_id",
     *     description="the blog id which this graph data belongs to",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer")),
     * @OA\Response(
     *    response=200,
     *    description="Successful response",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *       @OA\Property(property="response",type="object",
     *       @OA\Property(property="notes_count", type="integer", example=16),
     *       @OA\Property(property="new_followers_count", type="integer", example=326),
     *       @OA\Property(property="total_followers_count", type="integer", example=326),
     *       @OA\Property(property="data", type="object",
     *           example={
     *              {
     *               "timestamp": "2021-11-03 01:13:39",
     *               "total_followers": 5,
     *             },
     *             {
     *               "timestamp": "2021-17-03 01:13:39",
     *               "total_followers": 51,
     *             },
     *             {
     *               "timestamp": "2021-19-03 01:13:39",
     *               "total_followers": 9,
     *             },
     *           }),
     *    ),
     *   ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=404,
     *    description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
     *        )
     *     ),
     *  @OA\Response(
     *    response=500,
     *    description="Internal Server error",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
     *        )
     *     )
     * ),
     *
     */

    /**
    * get the new followers for a blog
    *
    * @param \GraphRequest $request
    * @return \json
    */
    public function getTotalFollwersGraphData(GraphRequest $request)
    {
        $request->validated();
        $data = DB::select('select count(*) as total_followers, created_at::date as timestamp 
            from follow_blog where followed_id = '
            . $request->blog_id .
            ' group by date(created_at) order by created_at::date;');

        if ($data) {
            for ($i = 1; $i < count($data); $i++) {
                $data[$i]->total_followers += $data[$i - 1]->total_followers;
            }
        }

        $blog = Blog::where('id', $request->blog_id)->first();
        $followers = $blog->followers()->count();
        $replies = $blog->replies()->count();

        $res = [
            "data" => $data,
            "notes_count" => $replies,
            "new_followers_count" => $followers,
            "total_followers_count" => $followers,
        ];

        return $this->generalResponse($res, "ok", 200);
    }

    /**
     * @OA\Get(
     * path="/blog_activity/{blog_id}",
     * summary="get number of likes ,posts, drafts ",
     * description=" return total_followings number, followings , likes of blog",
     * operationId="get blog activity",
     * tags={"Activity"},
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
     *         @OA\Property(property="response", type="object",
     *               @OA\Property(property="followers",type="integer",example=5),
     *               @OA\Property(property="followings",type="integer",example=5),
     *               @OA\Property(property="likes",type="integer",example=5),
     *                @OA\Property(property="posts",type="integer",example=5),
     *                @OA\Property(property="drafts",type="integer",example=5),
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
    * get activity numbers of blog
    * @param \Request $request
    * @param  $blogId
    * @return \json
    */

    public function getActivityBlog(Request $request, $blogId)
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
        $followings =  $blog->followings()->count();
        $posts = $blog->posts()->where('status', 'published')->count();
        $drafts = $blog->posts()->where('status', 'draft')->count();
        $likes = $blog->likes()->count();
        $reponse = [
            "followers" => $followers ,
            "followings" => $followings ,
            "posts" => $posts,
            "drafts" => $drafts,
            "likes" => $likes
        ];
        return  $this->generalResponse($reponse, "ok");
    }
}
