<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostRequestTest extends TestCase
{
    use RefreshDatabase;

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
    protected $blogId;
    /**
     * Set up the $post data, and grant access to the testing user before running each testcase
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
        $user = User::factory()->create();
        Passport::actingAs($user);
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        $this->blogId = $blog->id;
    }
    /**
     * Test the successful request
     *
     * @return void
     */
    public function testSuccessfulRequest()
    {
        $url = 'api/post/' . $this->blogId;
        $response = $this->json('POST', $url, $this->post, Config::JSON)
            ->assertStatus(200);
    }
    /**
     * Test the response message when post_body in the request body is not given
     *
     * @return void
     */
    public function testPostBodyRequired()
    {
        $this->post['post_body'] = "";
        $url = 'api/post/' . $this->blogId;
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testPostTypeRequired()
    {
        $this->post['post_type'] = "";
        $url = 'api/post/' . $this->blogId;
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testPostStatusRequired()
    {
        $this->post['post_status'] = "";
        $url = 'api/post/' . $this->blogId;
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
    public function testBlogIdNotNumber()
    {
        $url = 'api/post/' . 'sometext';
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testBlogIdDoesNotExist()
    {
        $url = 'api/post/' . '100000000';
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testPostStatusIsNotValid()
    {
        $url = 'api/post/' . $this->blogId;
        $this->post['post_status'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testPostTypeIsNotValid()
    {
        $url = 'api/post/' . $this->blogId;
        $this->post['post_type'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
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
     * @return void
     */
    public function testPostTimeIsNotValid()
    {
        $url = 'api/post/' . $this->blogId;
        $this->post['post_time'] = "SomeinvalidText";
        $response = $this
        ->json('POST', $url, $this->post, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post time is not a valid date.'
            ]
        ]);
    }
}
