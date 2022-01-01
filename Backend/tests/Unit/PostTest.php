<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

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
     * Testting the correctness of extracting mentions from a post content.
     *
     * @return void
     */
    public function testSuccessExtractedMentions()
    {
        $postService = new PostService();
        $postBody = '<h1>Hello</h1><p> my new friends: @david @august @december<p>';
        $expectedMentions = ['david', 'august', 'december'];
        $extractedMentions = $postService->extractMentionedBlogs($postBody);
        $this->assertEqualsCanonicalizing($expectedMentions, $extractedMentions);
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
        $user = User::factory()->create();
        Passport::actingAs($user);
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'is_primary' => true
        ]);
        //Creating the post
        $postBody = '<h1>#Hello</h1><p> This framework rocks! #piko #pako #pakapiko #maybe&nbsp<p>';
        $uri = '/api/post/' . $blog->id;
        $requestBody = [
            "post_body" => $postBody,
            "post_type" => "text",
            "post_status" => "published"
        ];

        $response = $this->json('POST', $uri, $requestBody, Config::JSON);
        $response->assertStatus(200);

        //Testing the creation of relation records in db        
        $expectedTags = ['Hello', 'piko', 'pako', 'pakapiko', 'maybe'];
        foreach ($expectedTags as $tag) {
            $this->assertDatabaseHas('post_tag', [
                'tag_description' => $tag,
                'post_id' => ($response->json())['response']['post_id']
            ]);
        }
    }

    /**
     * Testing the correctness of retrieving removed tags from a post whose content have been updated.
     *
     * @return void
     */
    public function testSuccessGetRemovedTags()
    {
        $postService = new PostService();
        $oldPostTags = $postService->extractTags('#Laravel #is #Super #Awesome');
        $newPostTags = $postService->extractTags('#Laravel #isnot #Cool');

        $actualRemovedTags = $postService->getRemovedTags($oldPostTags, $newPostTags);
        $expectedRemovedTags = ['Super', 'Awesome', 'is'];
        $this->assertEqualsCanonicalizing($expectedRemovedTags, $actualRemovedTags);
    }
}
