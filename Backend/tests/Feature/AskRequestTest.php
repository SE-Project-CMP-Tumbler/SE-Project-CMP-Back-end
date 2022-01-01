<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AskNotification;

class AskRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test missing question body.
     *
     * @return void
     */
    public function testRequiredQuestionBody()
    {
        Notification::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'allow_ask' => true,
            'allow_anonymous_questions' => true
            ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
                "question_flag" => false
        ];
        $response = $this
        ->json('POST', 'api/ask/' . ($blog->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The question body field is required.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test missing question flag.
     *
     * @return void
     */
    public function testRequiredQuestionFlag()
    {
        Notification::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'allow_ask' => true,
            'allow_anonymous_questions' => true
            ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => '<div><p>hello</p></div>'
        ];
        $response = $this
        ->json('POST', 'api/ask/' . ($blog->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The question flag field is required.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test wrong question body.
     *
     * @return void
     */
    public function testWrongQuestionBody()
    {
        Notification::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'allow_ask' => true,
            'allow_anonymous_questions' => true
            ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => 15,
            "question_flag" => false
        ];
        $response = $this
        ->json('POST', 'api/ask/' . ($blog->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The question body must be a string.",
            ]
        ]);
        Notification::assertNothingSent();
    }

    /**
     * A basic feature test wrong question flag.
     *
     * @return void
     */
    public function testWrongQuestionFlag()
    {
        Notification::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'allow_ask' => true,
            'allow_anonymous_questions' => true
            ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => "<div><p>mikl4</p></div>",
            "question_flag" => "false"
        ];
        $response = $this
        ->json('POST', 'api/ask/' . ($blog->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The question flag field must be true or false.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test non numeric blog id sent in the url.
     *
     * @return void
     */
    public function testWrongBlogId()
    {
        Notification::fake();
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => "<div><p>mikl4</p></div>",
            "question_flag" => "false"
        ];
        $response = $this
        ->json('POST', 'api/ask/a', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog id must be a number.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test the blog id doesn't exist.
     *
     * @return void
     */
    public function testNotExistBlogId()
    {
        Notification::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create();
        $blogId = $blog->id;
        $blog->delete();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => "<div><p>mikl4</p></div>",
            "question_flag" => "false"
        ];
        $response = $this
        ->json('POST', 'api/ask/' . $blogId, $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected blog id is invalid.",
            ]
        ]);
        Notification::assertNothingSent();
    }
    /**
     * A basic feature test success ask request.
     *
     * @return void
     */
    public function testAskRequest()
    {
        Notification::fake();
        $user = User::factory()->create();
        Blog::factory()->create(['user_id' => $user->id , 'is_primary' => true]);
        $blog = Blog::factory()->create([
            'allow_ask' => true,
            'allow_anonymous_questions' => true
            ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "question_body" => "<div><p>mikl4</p></div>",
            "question_flag" => false
        ];
        $response = $this
        ->json('POST', 'api/ask/' . ($blog->id), $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertstatus(200);
        Notification::assertSentTo(
            [$blog->user()->first()],
            AskNotification::class
        );
    }
}
