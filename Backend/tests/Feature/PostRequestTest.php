<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRequestTest extends TestCase
{
    /**
     * Hold the data of the post that will be modified within each test case
     *
     * @var array
     */
    protected $post;
    /**
     * The blog id of the currently authenticated user
     *
     * @var int
     */
    protected $blog_id;
    /**
     * The access token of the authenticated user that would do testing operations
     *
     * @var string
     */
    protected $access_token;
    /**
     * Set up the $post data, and require the access token for the testing user before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->post = [
            'post_body' => '<p>Is it worthy?</p>',
            'post_time' => now(),
            'post_type' => 'text',
            'post_status' => 'published'
        ];
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
        $this->blog_id = $blog->id;
        $this->access_token = $response['response']['access_token'];
    }
    /**
     * Test the successful request
     *
     * @test
     * @return void
     */
    public function successfulRequest()
    {
        $url = 'api/post/' . $this->blog_id;
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON);
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
        $this->post['post_body'] = "";
        $url = 'api/post/' . $this->blog_id;
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post body field is required.'
            ]
        ]);
    }
    /**
     * Test the response message when post_type in the request body is not given
     *
     * @test
     * @return void
     */
    public function postTypeRequired()
    {
        $this->post['post_type'] = "";
        $url = 'api/post/' . $this->blog_id;
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post type field is required.'
            ]
        ]);
    }
    /**
     * Test the response message when post_status in the request body is not given
     *
     * @test
     * @return void
     */
    public function postStatusRequired()
    {
        $this->post['post_status'] = "";
        $url = 'api/post/' . $this->blog_id;
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post status field is required.'
            ]
        ]);
    }
    /**
     * Test the response message when blog_id in the request url is not numeric
     *
     * @test
     * @return void
     */
    public function blogIdNotNumber()
    {
        $url = 'api/post/' . 'sometext';
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The blog id must be a number.'
            ]
        ]);
    }
    /**
     * Test the response message when blog_id in the request url
     * doesn't correspond to an existing blog
     *
     * @test
     * @return void
     */
    public function blogIdDoesNotExist()
    {
        $url = 'api/post/' . '100000000';
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The selected blog id is invalid.'
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
        $url = 'api/post/' . $this->blog_id;
        $this->post['post_status'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The selected post status is invalid.'
            ]
        ]);
    }
    /**
     * Test the response message when post type in the request body
     * is not a valid value
     *
     * @test
     * @return void
     */
    public function postTypeIsNotValid()
    {
        $url = 'api/post/' . $this->blog_id;
        $this->post['post_type'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
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
        $url = 'api/post/' . $this->blog_id;
        $this->post['post_time'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post time is not a valid date.'
            ]
        ]);
    }
}
