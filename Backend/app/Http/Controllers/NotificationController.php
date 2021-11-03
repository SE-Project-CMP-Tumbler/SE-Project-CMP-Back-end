<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

/**
 * @OA\Get(
 *  path="/blog/{blog_identifier}/notifications",
 *  operationId="getNotifications",
 *  tags={"Notifications"},
 *  security={ {"bearer": {} }},
 *  summary="retrieve blog's activity feed",
 *  description="Retrieve the activity items for a specific blog.",
 *  @OA\Parameter(
 *    in="path",
 *    name="blog_id",
 *    description="Any blog identifier.",
 *    required=true,
 *    example="mycoolblogname",
 *    @OA\Schema(
 *       type="string",
 *    )
 *  ),
 *  @OA\RequestBody(
 *   description="type: is a string parameter indicates which notification type to be recieved:
 *   like: a like on your post
 *   reply: a reply on your post
 *   follow: a new follower 
 *   mention_in_reply: a mention of your blog in a reply
 *   mention_in_post: a mention of your blog in a post
 *   reblog_naked: a reblog of your post, without commentary
 *   reblog_with_comment: a reblog of your post, with commentary
 *   ask: a new ask recieved 
 *   answer: an answered ask that you had sent
 *   all: to get all type of notifications",
 *   @OA\JsonContent(
 *       @OA\Property(property="response", type="object",
 *         @OA\Property(property="type", type="string", example="like|reply")
 *       ),
 *     ),
 *  ),
 *  @OA\Response(
 *    response=200,
 *    description="ok",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *      @OA\Property(property="response", type="object",
 *        @OA\Property(property="notifications", type="array",
 *          @OA\Items(
 *              @OA\Property(property="type", type="string", example="reply"),
 *              @OA\Property(property="timestamp", type="datetime", example="1635758986"),
 *              @OA\Property(property="before", type="datetime", example="1635758986"),
 *              @OA\Property(property="targetPostId", type="int", example="666639203021488128"),
 *              @OA\Property(property="targetPostSummary", type="string", example="Hello World"),
 *              @OA\Property(property="targetTumblelogName", type="string", example="lifeissimpleinwinter"),
 *              @OA\Property(property="targetTumblelogUuid", type="string", example="t:tvBkrCJiQ0cfnF57cZzMJg"),
 *              @OA\Property(property="fromTumblelogName", type="string", example="cpphelloworld"),
 *              @OA\Property(property="fromTumblelogUuid", type="string", example="t:KT-e9cdpJ1qB5VNcGemoKw"),
 *              @OA\Property(property="fromTumblelogIsAdult", type="bool", example="false"),
 *              @OA\Property(property="followed", type="bool", example="false"),
 *              @OA\Property(property="fromTumblelogAvatars", type="array", 
 *               @OA\Items(
 *                    @OA\Property(property="width", type="int", example="30"),
 *                    @OA\Property(property="height", type="int", example="30"),
 *                    @OA\Property(property="url", type="url", example="https://assets.tumblr.com/images/default_avatar/cone_open_30.png"),
 *                )
 *              ),
 *              @OA\Property(property="targetRootPostId", type="string", example="null"),
 *              @OA\Property(property="privateChanner", type="bool", example="false"),
 *              @OA\Property(property="targetPostType", type="string", example="reqular"),
 *              @OA\Property(property="postType", type="string", example="text"),
 *              @OA\Property(property="reblogKey", type="string", example="izXDvzwq"),
 *              @OA\Property(property="replyText", type="string", example="yes"),
 *              @OA\Property(property="diffToPrevious", type="string", example="yes")
 *          )
 *        ),
 *       ),
 *     ),
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *     @OA\Property(property="response", type="array",@OA\Items()),
 *     @OA\Property(property="errors", type="array",
 *       @OA\Items(
 *            @OA\Property(property="title", type="string", example="Unauthorized"),
 *            @OA\Property(property="code", type="int", example="5004"),
 *            @OA\Property(property="detail", type="string", example="Authentication/Login is required to access this resource/page"),
 *            @OA\Property(property="logout", type="bool", example="false"),
 *        )
 *       ),
 *     ),
 *  ),
 * )
 */
}
