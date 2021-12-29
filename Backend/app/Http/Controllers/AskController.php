<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Blog;
use App\Models\Post;
use App\Http\Misc\Helpers\Config;
use App\Http\Requests\AskRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\QuestionResource;
use App\Http\Requests\AnswerRequest;
use App\Notifications\AnswerNotification;
use App\Notifications\AskNotification;
use App\Services\AskService;

class AskController extends Controller
{
    //

 /**
 * @OA\Post(
 * path="/ask/{blog_id}",
 * summary="ask blog",
 * description=" Primary blog ask another blog",
 * operationId="askblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="Blog_id of asking blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\RequestBody(
 *    required=true,
 *    description="Question Asked body , question_flag show if anonymous or not ,true for anonymous and in case of anonymous , blog values will appear in response ",
 *     @OA\JsonContent(
 *         required={"question_body","question_flag"},
 *       @OA\Property(property="question_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *       @OA\Property(property="question_flag", type="boolean", example=false)
 *    )
 *  ),
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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
 *        )
 *     ),
 *   @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
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
    /**
     * add a ask on a post
     *
     * @param int $blogId
     * @param AskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ask($blogId, AskRequest $request)
    {
        $senderBlog = Blog::where([['user_id',$request->user()->id],['is_primary', true]])->first();
        $senderBlogID = ($senderBlog) ['id'];
        $ask = Question::create([
            'ask_sender_blog_id' => $senderBlogID,
            'ask_reciever_blog_id' => $blogId,
            'body' =>  $request->question_body,
            'anonymous_flag' => $request->question_flag,
        ]);

        // send notification about the ask
        $recieverBlog = Blog::where('id', $blogId)->first();
        $notifiedUser = $recieverBlog->user()->first();
        $notifiedUser->notify(new AskNotification($senderBlog, $recieverBlog, $ask));

        return $this->generalResponse("", "ok", 200);
    }
/**
 * @OA\Post(
 * path="/answer/{question_id}",
 * summary="create new post",
 * description=" A blog can answer question",
 * operationId="asnwerquestion",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="question_id",
 *          description="question_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *   @OA\RequestBody(
 *    required=true,
 *    description="Post/Answer Request has different types depeneds on answer type :
 *
 *     is genral : all fields can be given , to be genarl at least two different field of types should given" ,
 *    @OA\JsonContent(
 *       required={"post_status","post_type"},
 *
 *       @OA\Property(property="post_status", type="string", example="published"),
 *       @OA\Property(property="post_type", type="string", example="answer"),
 *       @OA\Property(property="post_time", type="string", example="2021-12-31"),
 *       @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *
 *
 *
 *
 *    ),
 * ),
 * @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response
 *      if the ask was (anonymous) the value of ( question_flag = false) and those values will equal an empty string: blog_username_asking -  blog_avatar_asking - blog_avatar_shape_asking - blog_title_asking - blog_id_asking
 *      but u will still get this value : ( question_body ) ",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *         @OA\Property(property="post_id", type="integer", example=5),
 *         @OA\Property(property="post_status", type="string", example="published"),
 *         @OA\Property(property="pinned", type="boolean", example=false),
 *         @OA\Property(property="post_time", type="date-time", example="02-02-2012"),
 *         @OA\Property(property="post_type", type="string", example="answer"),
 *         @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *         @OA\Property(property="blog_id", type="integer", example=5),
 *         @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.png"),
 *         @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *         @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_title", type="string", example="student"),
 *         @OA\Property(property="question_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *         @OA\Property(property="question_flag", type="boolean", example=false),
 *         @OA\Property(property="blog_avatar_asking", type="string", example="/storage/imgname2.png"),
 *         @OA\Property(property="blog_avatar_shape_asking", type="string", example="circle"),
 *         @OA\Property(property="blog_username_asking", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_title_asking", type="string", example="dr."),
 *         @OA\Property(property="blog_id_asking", type="integer", example=1032),
 *         @OA\Property(property="notes_count", type="integer", example=5),
 *         @OA\Property(property="is_liked", type="boolean", example=false),
 *
 *
 *     ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 *
 * ),
 *
 *
 *
 */
    /**
     * answer a question
     *
     * @param int $blogId
     * @param AnswerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function answer($questionId, AnswerRequest $request)
    {

        $question = Question::where('id', $questionId)->first();
        $publishedAt = ($request->post_time == null && ($request->post_status == 'published' || $request->post_status == 'private')) ? now() : $request->post_time;
        $post = Post::create([
            'status' => $request->post_status,
            'published_at' => $publishedAt,
            'body' => $request->post_body,
            'type' => 'answer',
            'blog_id' => $question->ask_reciever_blog_id
        ]);
        $answer = Answer::create([
            'ask_sender_blog_id' => $question->ask_sender_blog_id,
            'ask_reciever_blog_id' => $question->ask_reciever_blog_id,
            'post_id' =>  $post->id,
            'ask_body' => $question->body,
            'anonymous_flag' => $question->anonymous_flag,
        ]);

        // send notificaton about the answer
        $actorBlog = Blog::where('id', $answer->ask_sender_blog_id)->first();
        $recipientBlog = Blog::where('id', $answer->ask_reciever_blog_id)->first();
        $notifiedUser = $recipientBlog->user()->first();
        $notifiedUser->notify(new AnswerNotification($actorBlog, $recipientBlog, $question, $answer));

        $question->delete();
        return $this->generalResponse(new PostResource($post), "ok", 200);
    }

 /**
 * @OA\Delete(
 * path="/ask/{question_id}",
 * summary="ask blog",
 * description=" Primary blog delete ask from another blog",
 * operationId="deleteaskblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="question_id",
 *          description="question_id of asking blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *
 *
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
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
     * delete a question
     *
     * @param int $questionId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAsk($questionId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $questionId) == false) {
            return $this->generalResponse("", "The question id should be numeric.", "422");
        }

        $question = Question::where('id', $questionId)->first();
        if (empty($question)) {
            return $this->generalResponse("", "This question id is not found.", "404");
        }
        $question->delete();
        return $this->generalResponse("", "ok", 200);
    }
/**
 * @OA\get(
 * path="/messages/{blog_id}",
 * summary="get messages for a specific blog",
 * description=" get messages for a specific blog (submissions + asks)",
 * operationId="getMessagesForBlog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *    @OA\Parameter(
 *          name="blog_id",
 *          description="blog_id ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response
 *                 note: the messages array is a combination of both (asks and submissions) and they are different objects and u can differenciate between them using the post_status argument
 *                      either (post_status = submission) or (post_status = ask)
 *                  alert: as swagger let us only write one instant in the array so, i will write here the submission object response example and in the next route (/all_messages) i will write the ask object response example",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),),
 *       @OA\Property(property="messages", type="array",
 *          @OA\Items(
 *         @OA\Property(property="post_id", type="integer", example=5),
 *         @OA\Property(property="post_status", type="string", example="submission"),
 *         @OA\Property(property="pinned", type="boolean", example=false),
 *         @OA\Property(property="post_time", type="date-time", example="02-02-2012"),
 *         @OA\Property(property="post_type", type="string", example="submission"),
 *         @OA\Property(property="post_body", type="string", example="<div> <h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''> <p>It's the weapon that'd end the humanity!!</p> <video width='320' height='240' controls> <source src='movie.mp4' type='video/mp4'> <source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag. </video> <p>#AI #humanity #freedom</p> </div>"),
 *         @OA\Property(property="blog_id", type="integer", example=5),
 *         @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.png"),
 *         @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *         @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *         @OA\Property(property="blog_title", type="string", example="student"),
 *         @OA\Property(property="question_body", type="string", example=""),
 *         @OA\Property(property="question_flag", type="boolean", example=true),
 *         @OA\Property(property="blog_avatar_asking", type="string", example=""),
 *         @OA\Property(property="blog_avatar_shape_asking", type="string", example=""),
 *         @OA\Property(property="blog_username_asking", type="string", example=""),
 *         @OA\Property(property="blog_title_asking", type="string", example=""),
 *         @OA\Property(property="blog_id_asking", type="integer", example=""),
 *         @OA\Property(property="notes_count", type="integer", example=0),
 *         @OA\Property(property="is_liked", type="boolean", example=false),),
 *
 *      ),
 *     ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 *
 * ),
 *
 *
 *
 */
    /**
     * get all asks to a specific blog
     *
     * @param int $blogId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessagesForBlog($blogId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog Id should be numeric.", "422");
        }

        $blog = Blog::where('id', $blogId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This blog id is not found.", "404");
        }

        $askedQuestions = $blog->askedQuestions()->get();
        $submissionPosts = $blog->submissionPosts()->get();
        $currentPage = $request->input('page');


        return $this->generalResponse((new AskService())->mergeAndPaginate($askedQuestions, $submissionPosts, $currentPage), "OK");
    }
/**
 * @OA\get(
 * path="/all_messages",
 * summary="get all messages for a user",
 * description=" get all messages for a user (submissions + asks)",
 * operationId="getMessages",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *
 *     )
 * ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful response
 *                 note: the messages array is a combination of both (asks and submissions) and they are different objects and u can differenciate between them using the post_status argument
 *                      either (post_status = submission) or (post_status = ask)
 *                  alert: as swagger let us only write one instant in the array so, i will write here the ask object response example",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={ "status":"200","msg":"OK"}),
 *       @OA\Property(property="response", type="object",
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),),
 *       @OA\Property(property="messages", type="array",
 *          @OA\Items(
 *                        @OA\Property(property="question_id", type="integer", example=5),
 *                          @OA\Property(property="ask_time", type="date-time", example="02-02-2012"),
 *                       @OA\Property(property="post_status", type="string", example="ask"),
 *                       @OA\Property(property="blog_id", type="integer", example=5),
 *                     @OA\Property(property="blog_avatar", type="string", example="/storage/imgname2.png"),
 *                     @OA\Property(property="blog_avatar_shape", type="string", example="circle"),
 *                     @OA\Property(property="blog_username", type="string", example="radwa-ahmed213"),
 *                      @OA\Property(property="blog_title", type="string", example="student"),
 *                     @OA\Property(property="question_body", type="string", example=""),
 *                       @OA\Property(property="question_flag", type="boolean", example=false),),
 *
 *      ),
 *     ),
 *  ),
 * ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     )
 *
 * ),
 *
 *
 *
 */

    /**
     * get all asks to a specific User
     *
     * @param int $blogId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request)
    {

        $blogs = $request->user()->blogs;
        $askedQuestions = null;
        $submissionPosts = null;
        foreach ($blogs as $blog) {
            $askedQuestions = ($askedQuestions) ? $blog->askedQuestions->merge($askedQuestions) : $blog->askedQuestions;
            $submissionPosts = ($submissionPosts) ? $blog->submissionPosts->merge($submissionPosts) : $blog->submissionPosts;
        }
        $currentPage = $request->input('page');
        return $this->generalResponse((new AskService())->mergeAndPaginate($askedQuestions, $submissionPosts, $currentPage), "OK");
    }
 /**
 * @OA\Delete(
 * path="/messages/{blog_id}",
 * summary="delete all blog messages",
 * description=" delete all blog messages (submissions = asks)",
 * operationId="deleteaskblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="blog_id",
 *          description="blog_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *
 *
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
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
     * delete all asks to a specific blog
     *
     * @param int $blogId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessagesForBlog($blogId, Request $request)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog Id should be numeric.", "422");
        }

        $blog = Blog::where('id', $blogId)->first();
        if (empty($blog)) {
            return $this->generalResponse("", "This blog id is not found.", "404");
        }

        $askedQuestions = $blog->askedQuestions()->delete();
        $submissionPosts = $blog->submissionPosts()->detach();

        return $this->generalResponse("", "OK", 200);
    }
 /**
 * @OA\Delete(
 * path="/all_messages",
 * summary="delete all user messages",
 * description=" delete all user messages (submissions = asks) for all of his blogs",
 * operationId="deleteaskblog",
 * tags={"Ask Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *
 *
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
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
     * get all asks to a specific User
     *
     * @param int $blogId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessages(Request $request)
    {
        $blogs = $request->user()->blogs;
        foreach ($blogs as $blog) {
            $blog->askedQuestions()->delete();
            $blog->submissionPosts()->detach();
        }
        return $this->generalResponse("", "OK", 200);
    }
}
