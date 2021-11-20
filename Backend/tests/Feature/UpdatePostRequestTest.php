<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatePostRequestTest extends TestCase
{
    /**
     * Hold the data of the post that will be modified within each test case
     *
     * @var int
     */
    protected $post_id;
    /**
     * The blog id of the currently authenticated user
     *
     * @var int
     */
    protected $blog_id = 12;
    /**
     * Set up the $post data before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->post_id = Post::create([
            'body' => 'This is not nice!',
            'type' => 'general',
            'status' => 'draft',
            'published_at' => now(),
            'blog_id' => $this->blog_id
        ])->id;
    }
    /**
     * Test the response message when post_body in the request body is not given
     *
     * @test
     * @return void
     */
    public function postBodyRequired()
    {
        $data = [
            'post_body' => ""
        ];
        $url = 'api/post/' . $this->post_id;
        $response = $this
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post body field is required.'
            ]
        ]);
    }
    /**
     * Test the response message when post status in the request body
     * is not a valid value
     *
     * @test
     * @return void
     */
    public function postStatusIsNotValid()
    {
        $url = 'api/post/' . $this->post_id;
        $data = [
            'post_status' => "SomeinvalidText"
        ];
        $response = $this
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The selected post status is invalid.'
            ]
        ]);
    }
    /**
     * Test the response message when post status in the request body
     * is not a valid value
     *
     * @test
     * @return void
     */
    public function postTypeIsNotValid()
    {
        $url = 'api/post/' . $this->post_id;
        $data = [
            'post_type' => "SomeinvalidText"
        ];
        $response = $this
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The selected post type is invalid.'
            ]
        ]);
    }
    /**
     * Test the response message when post time in the request body
     * is not a valid date
     *
     * @test
     * @return void
     */
    public function postTimeIsNotValid()
    {
        $url = 'api/post/' . $this->post_id;
        $data = [
            'post_time' => "SomeinvalidText"
        ];
        $response = $this
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post time is not a valid date.'
            ]
        ]);
    }
    /**
     * Test the response message when pinned in the request body
     * is not a valid boolean value
     *
     * @test
     * @return void
     */
    public function pinnedIsNotValid()
    {
        $url = 'api/post/' . $this->post_id;
        $data = [
            'pinned' => "SomeinvalidText"
        ];
        $response = $this
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The pinned field must be true or false.'
            ]
        ]);
    }
}
