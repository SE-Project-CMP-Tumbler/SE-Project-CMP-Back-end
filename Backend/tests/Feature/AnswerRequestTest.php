<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Question;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AnswerNotification;

class AnswerRequestTest extends TestCase
{
    use RefreshDatabase;

   /**
     * A basic feature test missing post body.
     *
     * @return void
     */
    public function testRequiredPostBody()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_type" => "answer"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The post body field is required.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test missing post type.
     *
     * @return void
     */
    public function testRequiredPostType()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_body" => "<div><p>hello back</p></div>"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The post type field is required.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test invalid post type.
     *
     * @return void
     */
    public function testInvalidPostType()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_body" => "<div><p>hello back</p></div>",
            "post_type" => "post"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected post type is invalid.",
            ]
        ]);
        Notification::assertNothingSent();
    }
        /**
     * A basic feature test missing post status.
     *
     * @return void
     */
    public function testRequiredPostStatus()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_type" => "answer",
            "post_body" => "<div><p>hello back</p></div>"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The post status field is required.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test invalid post status.
     *
     * @return void
     */
    public function testInvalidPostStatus()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "wrongStatus",
            "post_body" => "<div><p>hello back</p></div>",
            "post_type" => "answer"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected post status is invalid.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test invalid question id sent in the url.
     *
     * @return void
     */
    public function testInvalidQuestionId()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_body" => "<div><p>hello back</p></div>",
            "post_type" => "answer"
        ];
        $response = $this
        ->json('POST', 'api/answer/k', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The question id must be a number.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test question does not exist  id sent in the url.
     *
     * @return void
     */
    public function testNotExistQuestionId()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create();
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $questionId = $question->id;
        $question->delete();
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_body" => "<div><p>hello back</p></div>",
            "post_type" => "answer"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($questionId), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected question id is invalid.",
            ]
        ]);
        Notification::assertNothingSent();
    }
   /**
     * test success answer question.
     *
     * @return void
     */
    public function testAnswerRequest()
    {
        Notification::fake();
        $blogSender = Blog::factory()->create();
        $blogReciever = Blog::factory()->create(['is_primary' => true]);
        $question = Question::factory()->create([
            'ask_sender_blog_id' => $blogSender,
            'ask_reciever_blog_id' => $blogReciever,
            'body' =>  '<div><p>hello</p></div>',
            'anonymous_flag' => false,
            ]);
        $token = $blogReciever->user->createToken('Auth Token')->accessToken;
        $request = [
            "post_status" => "published",
            "post_body" => "<div><p>hello back</p></div>",
            "post_type" => "answer"
        ];
        $response = $this
        ->json('POST', 'api/answer/' . ($question->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertStatus(200);
        Notification::assertSentTo(
            [$blogReciever->user()->first()],
            AnswerNotification::class
        );
    }
}
