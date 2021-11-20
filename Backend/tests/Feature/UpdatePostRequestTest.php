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
     * The access token of the authenticated user that would do testing operations
     *
     * @var string
     */
    protected $access_token;
    /**
     * Set up the $post data before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create(1);
        $request_body = [
            "email" => $faker->email(),
            "blog_username" => $faker->text(),
            "password" => "testTest1234",
            "age" => "22"
        ];
        $response = $this->json('POST', 'api/register', $request_body, Config::JSON);
        $user_id = $response['response']['id'];
        $blog = Blog::factory()->create(['user_id' => $user_id]);
        $this->access_token = $response['response']['access_token'];

        $this->post_id = Post::create([
            'body' => 'This is not nice!',
            'type' => 'general',
            'status' => 'draft',
            'published_at' => now(),
            'blog_id' => $blog->id
        ])->id;
    }
    /**
     * Test the successful request
     *
     * @test
     * @return void
     */
    public function successfulRequest()
    {
        $url = 'api/post/' . $this->post_id;
        $post = [
            'post_body' => "Hello this is a post body.",
            'post_time' => now(),
            'post_status' => 'draft',
            'post_type' => 'general'
        ];
        $response = $this
        ->json('PUT', $url, $post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON);
        $this->assertTrue($response->json()["meta"]["status"] === "200");
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
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
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
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
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
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
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
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
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
        ->json('PUT', $url, $data, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The pinned field must be true or false.'
            ]
        ]);
    }
}
