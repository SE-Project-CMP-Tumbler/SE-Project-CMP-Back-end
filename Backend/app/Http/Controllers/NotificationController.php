<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{
    /**
     *  @OA\Get(
     *  path="/blog/notifications?type={type}&from_blog_id={from_blog_id}",
     *  operationId="getNotifications",
     *  tags={"Notifications"},
     *  security={ {"bearer": {} }},
     *  summary="retrieve blog's activity feed",
     *  description="Retrieve the activity items for a specific blog.",
     *  @OA\Parameter(
     *    in="query",
     *    name="type",
     *    description="
     *        type: is optional to indicate which of these notificaions to get its and default is all
     *        like: a like on your post
     *        reply: a reply on your post
     *        follow: a new follower
     *        reblog: a reblog of your post
     *        ask: a new ask recieved
     *        answer: an answered ask that you had sent
     *        mentions_posts: get mentions in posts
     *        mentions_replies: get mentions in replies
     *        all: get all types of notifications",
     *    required=false,
     *    example="follow",
     *    @OA\Schema(
     *       type="string",
     *    )
     *  ),
     *  @OA\Parameter(
     *    in="query",
     *    name="for_blog_id",
     *    description="
     *        is optional to retreive the notificaion for specific one of your blogs, default is you primary blog",
     *    required=false,
     *    example="123456789",
     *    @OA\Schema(
     *       type="int",
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="
     *      Successful Operation,
     *      notificaion_id: is a unique id for each notificaion
     *      type: is the notification type { follow | mention | like | reply | reblog }
     *      timestamp: is when that action happened,
     *
     *      target_blog_id: is one of the users receiving these notificaions blog ids to know
     *      each of there blogs has been { followed | mentioned | got a like | got a reply | got a reblog }
     *
     *      if the notificaion is of type { follow }, there will be the { follower_id }
     *      which is the id of the user made the follow,
     *
     *      if that notification is of type { mention | like | reply | rebolg }, the response will
     *      have { target_post_id, target_post_type, target_post_summary }
     *
     *      { from_blog_id | from_blog_username | from_blog_avatar_shape | from_blog_avatar }
     *      those attributes belong to the blog that made the action,
     *
     *      follow: this checks if you follow the other user which his blog made that action",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta",type="object",example={"status":"200", "msg":"ok"}),
     *      @OA\Property(property="response",type="object",
     *        @OA\Property(property="notificatoins",type="object",
     *         example={
     *             {
     *                 "type":"mention",
     *                 "notification_id":"ec4ec34a-1434-4aad-ae0a-7c8446bcf8d3",
     *                 "timestamp":"2021-12-29T07:33:29.000000Z",
     *                 "target_blog_id":39,
     *                 "target_post_id":1523,
     *                 "target_post_type":"text",
     *                 "target_post_summary":"<p> this is post summary </p>",
     *                 "from_blog_id":84,
     *                 "from_blog_username":"helloBLog",
     *                 "from_blog_avatar":"/storage/imagename.ext",
     *                 "from_blog_avatar_shape":"circle",
     *                 "follow":"false",
     *             },
     *             {
     *                 "type":"ask",
     *                 "notification_id":"ec4ec34a-1434-4aad-ae0a-7c8446bcf8d3",
     *                 "timestamp":"2021-12-29T07:33:29.000000Z",
     *                 "target_blog_id":39,
     *                 "target_question_id":1523,
     *                 "target_question_summary":"<p> this is post summary </p>",
     *                 "from_blog_id":84,
     *                 "from_blog_username":"helloBLog",
     *                 "from_blog_avatar":"/storage/imagename.ext",
     *                 "from_blog_avatar_shape":"circle",
     *                 "follow":"false",
     *             },
     *             {
     *                 "type":"like",
     *                 "notification_id":"ec4ec34a-1434-4aad-ae0a-7c8446bcf8d3",
     *                 "timestamp":"2021-12-29T07:33:29.000000Z",
     *                 "target_blog_id":39,
     *                 "target_post_id":1523,
     *                 "target_post_type":"text",
     *                 "target_post_summary":"<p> this is post summary </p>",
     *                 "from_blog_id":84,
     *                 "from_blog_username":"helloBLog",
     *                 "from_blog_avatar":"/storage/imagename.ext",
     *                 "from_blog_avatar_shape":"circle",
     *                 "follow":"false",
     *             },
     *             {
     *                 "type":"follow",
     *                 "notification_id":"ec4ec34a-1434-4aad-ae0a-7c8446bcf8d3",
     *                 "timestamp":"2021-12-29T07:33:29.000000Z",
     *                 "target_blog_id":39,
     *                 "from_blog_id":84,
     *                 "from_blog_username":"helloBLog",
     *                 "from_blog_avatar":"/storage/imagename.ext",
     *                 "from_blog_avatar_shape":"circle",
     *                 "follow":"false",
     *             },
     *             {
     *                 "type":"reply",
     *                 "notification_id":"ec4ec34a-1434-4aad-ae0a-7c8446bcf8d3",
     *                 "timestamp":"2021-12-29T07:33:29.000000Z",
     *                 "target_blog_id":39,
     *                 "target_post_id":1523,
     *                 "target_post_type":"text",
     *                 "target_post_summary":"<p> this is post summary </p>",
     *                 "reply_id":1523,
     *                 "reply_summary":"<p> this is post summary </p>",
     *                 "from_blog_id":84,
     *                 "from_blog_username":"helloBLog",
     *                 "from_blog_avatar":"/storage/imagename.ext",
     *                 "from_blog_avatar_shape":"circle",
     *                 "follow":"false",
     *             },
     *        }),
     *         @OA\property(property="pagination",type="object",
     *             @OA\property(property="total",type="int",example=17),
     *             @OA\property(property="count",type="int",example=7),
     *             @OA\property(property="per_page",type="int",example=10),
     *             @OA\property(property="current_page",type="int",example=2),
     *             @OA\property(property="total_pages",type="int",example=2),
     *             @OA\property(property="first_page_url",type="boolean",example=false),
     *             @OA\property(property="next_page_url",type="string",example=null),
     *             @OA\property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/posts/notificaions?page=1"),),
     *      ),
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
     *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
     *        )
     * ),
     *   @OA\Response(
     *    response=404,
     *    description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"})
     *        )
     * ),
     * ),
     */


    /**
     * get all the notificaions for the current logged in user
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function notificaions(NotificationRequest $request)
    {
        $request->validated();
        $curUser = $request->user();
        $curUnreadUserNotification = $curUser->unreadNotifications();
        if ($request->filled('type') && $request->type !== 'all') {
            $curUnreadUserNotification->whereJsonContains("data", ["type" => $request->type]);
        }
        if ($request->filled('for_blog_id')) {
            $curUnreadUserNotification->whereJsonContains("data", ["target_blog_id" => (int) $request->for_blog_id]);
        }
        $curUnreadUserNotification = $curUnreadUserNotification->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse(new NotificationCollection($curUnreadUserNotification), "ok", "200");
    }
}
