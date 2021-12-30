<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\GraphRequest;
use App\Services\BlogService;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityService;

class ActivityController extends Controller
{
    /**
     * @OA\Get(
     * path="/graph/notes/{blog_id}/{period}/{rate}",
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
     *  @OA\Parameter(
     *          name="period",
     *          description="the time period that you want to retrieve the data for.
     *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
     *  @OA\Parameter(
     *          name="rate",
     *          description="the time rate that you want to retrieve the data with.
     *  ( 0 -> hourly) , (1 -> daily),
     *  note: if the period=1, then the rate must equal 0.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
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
        $activityService = new ActivityService();
        $data = $activityService->getNotesService($request->blog_id, $request->period, $request->rate);

        $newFollowersCount = $activityService->countNewFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $TotalFollowersCount = $activityService->countTotalFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $notesCount = $activityService->countNotesService(
            $request->blog_id,
            $request->period,
            $request->rate
        );

        $res = [
            "data" => $data,
            "notes_count" => $notesCount,
            "new_followers_count" => $newFollowersCount,
            "total_followers_count" => $TotalFollowersCount,
        ];

        return $this->generalResponse($res, "ok", 200);
    }

    /**
     * @OA\Get(
     * path="/graph/new_followers/{blog_id}/{period}/{rate}",
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
     *  @OA\Parameter(
     *          name="period",
     *          description="the time period that you want to retrieve the data for.
     *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
     *  @OA\Parameter(
     *          name="rate",
     *          description="the time rate that you want to retrieve the data with.
     *  ( 0 -> hourly) , (1 -> daily),
     *  note: if the period=1, then the rate must equal 0.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
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
        $activityService = new ActivityService();
        $data = $activityService->getNewFollowersService($request->blog_id, $request->period, $request->rate);

        $newFollowersCount = $activityService->countNewFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $TotalFollowersCount = $activityService->countTotalFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $notesCount = $activityService->countNotesService(
            $request->blog_id,
            $request->period,
            $request->rate
        );

        $res = [
            "data" => $data,
            "notes_count" => $notesCount,
            "new_followers_count" => $newFollowersCount,
            "total_followers_count" => $TotalFollowersCount,
        ];

        return $this->generalResponse($res, "ok", 200);
    }

    /**
     * @OA\Get(
     * path="/graph/total_followers/{blog_id}/{period}/{rate}",
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
     *  @OA\Parameter(
     *          name="period",
     *          description="the time period that you want to retrieve the data for.
     *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
     *  @OA\Parameter(
     *          name="rate",
     *          description="the time rate that you want to retrieve the data with.
     *  ( 0 -> hourly) , (1 -> daily),
     *  note: if the period=1, then the rate must equal 0.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")),
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
        $activityService = new ActivityService();
        $data = $activityService->getTotalFollowersService($request->blog_id, $request->period, $request->rate);

        $newFollowersCount = $activityService->countNewFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $TotalFollowersCount = $activityService->countTotalFollowersService(
            $request->blog_id,
            $request->period,
            $request->rate
        );
        $notesCount = $activityService->countNotesService(
            $request->blog_id,
            $request->period,
            $request->rate
        );

        $res = [
            "data" => $data,
            "notes_count" => $notesCount,
            "new_followers_count" => $newFollowersCount,
            "total_followers_count" => $TotalFollowersCount,
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
