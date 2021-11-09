<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    /**
 * @OA\Get(
 * path="/search/{word}",
 * summary="Search about word in blogs or in posts",
 * description=" Get all blogs which contains word or posts which contains word",
 * operationId="search",
 * tags={"Search"},
 * *  @OA\Parameter(
 *          name="word",
 *          description="Word to Search about",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_id", type="integer", example=5),
 *                  @OA\Property(property="blog_id", type="integer", example=5),
 *                  @OA\Property(property="post_tags", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="books"),
 *                          @OA\Property(property="1", type="string", example="reading"),
 *                          @OA\Property(property="2", type="string", example="stay positive"),)),
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="post_time",type="date_time",example="02-02-2012"),
 *                  @OA\Property(property="link",type="string",example="facebook.com"),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/images.png"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/images.png"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/images.png"),
 *                  )
 *           ),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),))) ),
 *                 @OA\Property(property="blogs",type="array",
 *                   @OA\Items(
 *                    @OA\Property(property="id", type="integer", example=2026),
 *                    @OA\Property(property="username", type="string", example="newinvestigations"),
 *                    @OA\Property(property="avatar", type="string", format="byte", example=""),
 *                    @OA\Property(property="avatar_shape", type="string", example="square"),
 *                    @OA\Property(property="header_image", type="string", format="byte", example=""),
 *                    @OA\Property(property="title", type="string", example="My 1st Blog"),
 *                    @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),))
 *        ),
 *     ),
 * ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *
 */
}
