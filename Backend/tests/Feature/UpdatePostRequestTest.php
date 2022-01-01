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

class UpdatePostRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Hold the data of the post that will be modified within each test case
     *
     * @var int
     */
    protected $postId;
    /**
     * Set up the $post data before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Passport::actingAs($user);
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);

        $this->postId = Post::create([
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
        $url = 'api/post/' . $this->postId;
        $post = [
            'post_body' => "Hello this is a post body.",
            'post_time' => now(),
            'post_status' => 'draft',
            'post_type' => 'general'
        ];
        $response = $this->json('PUT', $url, $post, Config::JSON)
            ->assertStatus(200);
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
        $url = 'api/post/' . $this->postId;
        $response = $this->json('PUT', $url, $data, Config::JSON)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => 'The post body field is required.'
                ]
            ]);
    }
    /**
     * Test the response message when post status in the request body is not a valid value
     *
     * @test
     * @return void
     */
    public function postStatusIsNotValid()
    {
        $url = 'api/post/' . $this->postId;
        $data = [
            'post_status' => "SomeinvalidText"
        ];
        $response = $this->json('PUT', $url, $data, Config::JSON)
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
        $url = 'api/post/' . $this->postId;
        $data = [
            'post_type' => "SomeinvalidText"
        ];
        $response = $this->json('PUT', $url, $data, Config::JSON)
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
        $url = 'api/post/' . $this->postId;
        $data = [
            'post_time' => "SomeinvalidText"
        ];
        $response = $this->json('PUT', $url, $data, Config::JSON)
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
        $url = 'api/post/' . $this->postId;
        $data = [
            'pinned' => "SomeinvalidText"
        ];
        $response = $this->json('PUT', $url, $data, Config::JSON)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => 'The pinned field must be true or false.'
                ]
            ]);
    }
}
