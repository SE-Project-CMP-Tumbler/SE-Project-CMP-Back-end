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
        $post = Post::factory()->create();
        $faker = Factory::create(1);
        $this->tag = [
            'tag_description' => $faker->text(),
            'post_id' => $post->id
        ];
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
        ->json('POST', $url, $body, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => 'The post id must be a number.'
            ]
        ]);
    }
}
