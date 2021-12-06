<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogSettingRequestTest extends TestCase
{
   /**
     *  test Blog allow_message is boolean
     *
     * @return void
     */

    public function testAllowMessage()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "allow_messages" => 5,
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The allow messages field must be true or false."
            ]
        ]);
    }
    /**
     *  test Blog submissions_guidelines  has at least 3 chars
     *
     * @return void
     */
    public function testSubmissionsGuideline()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "submissions_guidelines" => false,
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The submissions guidelines must be a string."
            ]
        ]);
    }
    /**
     *  test Blog submissions_page_title has at least 3 chars and string
     *
     * @return void
     */
    public function testSubmissionpageTitle()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "submissions_page_title" => true
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The submissions page title must be a string."
            ]
        ]);
    }
      /**
     *  test Blog allow_ask must be boolean
     *
     * @return void
     */
    public function testAllowAsk()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "allow_ask" => "Radwa",
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The allow ask field must be true or false."
            ]
        ]);
    }
    /**
     *  test Blog ask_page_title has at least 3 chars
     *
     * @return void
     */
    public function testAskPageTitle()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "ask_page_title" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The ask page title must be at least 3 characters.",
            ]
        ]);
    }
    /**
     *  test Blog allow_anonymous_questions must be boolean
     *
     * @return void
     */
    public function testAllowAnonymousQuestion()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "allow_anonymous_questions" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The allow anonymous questions field must be true or false.",
            ]
        ]);
    }
    /**
     *  test Blog allow_submittions must be boolean
     *
     * @return void
     */
    public function testAllowSubmittion()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "allow_submittions" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The allow submittions field must be true or false."
            ]
        ]);
    }
    /**
     *  test Blog replies_settings must contain specific values
     *
     * @return void
     */
    public function testReplySetting()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "replies_settings" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected replies settings is invalid.",
            ]
        ]);
    }
     /**
     *  test Blog blog_settings are correct
     *
     * @return void
     */
    public function testTrueSetting()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('Auth Token')->accessToken;
        $setting = [
            "allow_anonymous_questions" => true
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/' . $blog->id, $setting, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertStatus(200);
    }
}
