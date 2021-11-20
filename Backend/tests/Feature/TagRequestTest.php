<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagRequestTest extends TestCase
{
    /**
     * @var array $tag
     */
    protected $tag;
    /**
     * Set up the $tag data before running each testcase
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

        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->tag = [
            'tag_description' => $faker->text(),
            'post_id' => $post->id
        ];
    }
    /**
     * Test the successful request
     *
     * @test
     * @return void
     */
    public function successfulRequest()
    {
        $url = 'api/tag/data/' . $this->tag['post_id'] . '/' . $this->tag['tag_description'];
        $body = [];
        $response = $this
        ->json('POST', $url, $body, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON);
        $this->assertTrue($response->json()["meta"]["status"] === "200");
    }
    /**
     * Test the reponse message when post id is not numeric
     *
     * @test
     * @return void
     */
    public function postIdNotNumber()
    {
        $this->tag['post_id'] = "somestring";
        $url = 'api/tag/data/' . $this->tag['post_id'] . '/' . $this->tag['tag_description'];
        $body = [];
        $response = $this
        ->json('POST', $url, $body, ['Authorization' => 'Bearer ' . $this->access_token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post id must be a number.'
            ]
        ]);
    }
}
