<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\User;
use App\Services\PostService;
use Faker\Factory;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * The access token of the authenticated user that would do testing operations
     *
     * @var string
     */
    protected $accessToken;
    /**
     * The blog id of the currently authenticated user
     *
     * @var int
     */
    protected $blogId;
    /**
     * Require the access token for the testing user before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $faker = Factory::create(1);
        $requestBody = [
            'email' => $faker->email(),
            'blog_username' => $faker->name(),
            'password' => 'testTest123',
            'age' => 77
        ];
        $response = $this->json('POST', '/api/register', $requestBody, Config::JSON);
        $this->accessToken = ($response->json())['response']['access_token'];
        $userId = ($response->json())['response']['id'];
        $blog = Blog::factory()->create(['user_id' => $userId]);
        $this->blogId = $blog->id;
    }
    /**
     * Testting the correctness of extracting tags from a post content.
     *
     * @return void
     */
    public function testSuccessExtractedTags()
    {
        $postService = new PostService();
        $postBody = '<h1>Laravel is awesome</h1><p> This framework rocks! #laravel #awesome #maybe&nbsp #backend<p>';
        $expectedTags = ['laravel', 'awesome', 'maybe', 'backend'];
        $extractedTags = $postService->extractTags($postBody);
        $this->assertEqualsCanonicalizing($expectedTags, $extractedTags);
    }

    /**
     * Testting the failure of extracting tags from a post content.
     *
     * @return void
     */
    public function testFailedExtractedTags()
    {
        $postService = new PostService();
        $postBody = '<h1>Laravel is awesome</h1><p> This framework rocks! #laravel #awesome #maybe&nbsp #backend<p>';
        $expectedTags = ['laravel', 'awesome', '#backend<p>','flutter', '#maybe&nbsp'];
        $extractedTags = $postService->extractTags($postBody);
        $this->assertNotEqualsCanonicalizing($expectedTags, $extractedTags);
    }

    /**
     * Testting the creation of post tags relationship records in DB while creating a post.
     *
     * @return void
     */
    public function testPostTagRelationCreation()
    {
        $postBody = '<h1>Laravel is awesome</h1><p> This framework rocks! #piko #pako #pakapiko<p>';
        $uri = '/api/post/' . $this->blogId;
        $requestBody = [
            "post_body" => $postBody,
            "post_type" => "text",
            "post_status" => "published"
        ];
        $response = $this
        ->json('POST', $uri, $requestBody, ['Authorization' => 'Bearer ' . $this->accessToken], Config::JSON);
        $expectedTags = ['piko', 'pako', 'pakapiko'];
        foreach ($expectedTags as $tag) {
            $this->assertDatabaseHas('post_tag', [
                'tag_description' => $tag,
                'post_id' => ($response->json())['response']['post_id']
            ]);
        }
    }
}
