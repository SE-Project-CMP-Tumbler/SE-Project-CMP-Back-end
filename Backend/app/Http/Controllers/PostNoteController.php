<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Misc\Helpers\Config;
use App\Http\Resources\LikeCollection;
use App\Http\Resources\ReplyCollection;
use App\Http\Resources\ReblogNotesCollection;

class PostNoteController extends Controller
{

 /**
 * @OA\Get(
 * path="/post_notes/{post_id}",
 * summary="Gets all notes for a specific post by post id",
 * description="Returns a list of all notes attached to a specific post",
 * operationId="getPostNotesByPostId",
 * tags={"Post Notes"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="post_id",
 *          description="The Post Id for the notes to be retrieved",
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
 *         @OA\Property(property="likes" ,type= "object",
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/post_notes/{post_id}?page=1"),),
 *          @OA\Property(property="likes",type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="Positive Quotes"),
 *                      @OA\Property(property="blog_id", type="integer", example=1032),
 *                      @OA\Property(property="followed", type="boolean", example=false),),), ),
 *          @OA\Property(property="replies" ,type="object",
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/post_notes/{post_id}?page=1"),),
 *          @OA\Property(property="replies",type="array",
 *              @OA\Items(
 *                      @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.extension"),
 *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_id", type="integer", example=2),
 *                      @OA\Property(property="followed", type="boolean", example=false),
 *                      @OA\Property(property="reply_id", type="integer", example=5),
 *                      @OA\Property(property="reply_time", type="date-time", example="02-02-2012"),
 *                      @OA\Property(property="reply_text", type="string", example="What an amazing post!"),),),),
 *   @OA\Property(property="reblogs", type="object" ,
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/post_notes/{post_id}?page=1"),),
 *           @OA\Property(property="reblogs",type="array",
 *             @OA\Items(
    *                      @OA\Property(property="post_id", type="integer", example=5),
    *                      @OA\Property(property="blog_avatar", type="string", format="byte", example="/storage/imgname2.extension"),
    *                      @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
    *                      @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
    *                      @OA\Property(property="blog_id", type="integer", example=2),
    *                      @OA\Property(property="reblog_content", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),)),
 *      ),
 *        ),
 *     ),
 * ),
 *
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
    /**
     * get all notes of a post a post
     *
     * @param int $postId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotes($postId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $postId) == false) {
            return $this->generalResponse("", "The post Id should be numeric.", "422");
        }

        $blog = Post::where('id', $postId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This post id is not found.", "404");
        }

        $post = Post::find($postId);

        $likes = $post->likes(Config::PAGINATION_LIMIT);
        $replies =  $post->replies()->latest()->paginate(Config::PAGINATION_LIMIT);
        $reblogs = Post::where('parent_id', $postId)->latest()->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse([
            "likes" => new LikeCollection($likes),
            "replies" => new ReplyCollection($replies),
            "reblogs" => new ReblogNotesCollection($reblogs)
        ], "ok");
    }
}
