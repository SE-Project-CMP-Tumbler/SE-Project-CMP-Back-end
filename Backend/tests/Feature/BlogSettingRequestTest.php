<?php

namespace Tests\Feature;

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


        $setting = [
            "allow_messages" => 5,
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
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

        $setting = [
            "submissions_guidelines" => false,
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The submissions guidelines must be a string.The submissions guidelines must be at least 3 characters."
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
        $setting = [
            "submissions_page_title" => true
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The submissions page title must be a string.The submissions page title must be at least 3 characters."
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

        $setting = [
            "allow_ask" => "Radwa",
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
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

        $setting = [
            "ask_page_title" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
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

        $setting = [
            "allow_anonymous_questions" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
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

        $setting = [
            "allow_submittions" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
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

        $setting = [
            "replies_settings" => "Ra"
        ];
        $response = $this
        ->json('PUT', 'api/blog_settings/2', $setting, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected replies settings is invalid.",
            ]
        ]);
    }
}
