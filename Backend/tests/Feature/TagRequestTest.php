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

class TagRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array $tag
     */
    protected $tag;
    /**
     * Set up the post has tag relationship data before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create(1);
        $user = User::factory()->create();
        Passport::actingAs($user);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

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
        $response = $this->json('POST', $url, $body, Config::JSON)
            ->assertStatus(200);
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
        $response = $this->json('POST', $url, $body, Config::JSON)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => 'The post id must be a number.'
                ]
            ]);
    }
}
