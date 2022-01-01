<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The blog id of the currently authenticated user
     *
     * @var int
     */
    protected $blogId;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Passport::actingAs($user);
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        $this->blogId = $blog->id;
    }
    /**
     * Testing the response message of updating a post with a non-integer post id
     *
     * @return void
     */
    public function testNonNumericPostIdToUpdatePost()
    {
        $url = '/api/post/' . '123textid';
        $response = $this->put($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The post id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of updating a non existing post
     *
     * @return void
     */
    public function testUpdateNonExistingPost()
    {
        $url = '/api/post/' . '100';
        $response = $this->put($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "This post was not found"
                ]
            ]);
    }
    /**
     * Testing the response message of deleteing a post with a non-integer post id
     *
     * @return void
     */
    public function testNonNumericPostIdToDeletePost()
    {
        $url = '/api/post/' . '123textid';
        $response = $this->delete($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The post id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of deleting a non existing post
     *
     * @return void
     */
    public function testDeleteNonExistingPost()
    {
        $url = '/api/post/' . '100';
        $response = $this->delete($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "This post doesn't exist"
                ]
            ]);
    }
    /**
     * Testing the response message of approving a post with a non-integer post id
     *
     * @return void
     */
    public function testNonNumericPostIdToApproveSubmission()
    {
        $url = '/api/post/approve/' . '123textid';
        $response = $this->post($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The post id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of approving a post that is not a submission
     *
     * @return void
     */
    public function testApproveNonSubmissionPost()
    {
        $post = Post::factory()->create(['status' => 'published', 'blog_id' => $this->blogId]);
        $url = '/api/post/approve/' . $post->id;
        $response = $this->post($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The id specified is not a submission post."
                ]
            ]);
    }
    /**
     * Testing the response message of creating a reblog with a non-integer parent id
     *
     * @return void
     */
    public function testNonNumericParentIdToCreateReblog()
    {
        $url = '/api/reblog/' . $this->blogId . '/' . '123textid';
        $response = $this->post($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "post_parent_id should be numeric"
                ]
            ]);
    }
    /**
     * Testing the response message of creating a reblog with a non-integer blog id
     *
     * @return void
     */
    public function testNonNumericBlogIdToCreateReblog()
    {
        $post = Post::factory()->create(['blog_id' => $this->blogId]);
        $url = '/api/reblog/' . '123textblogId' . '/' . $post->id;
        $response = $this->post($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "blog_id should be numeric"
                ]
            ]);
    }
    /**
     * Testing the response message of creating a reblog with a non existing parent post
     *
     * @return void
     */
    public function testCreateReblogOnNonExistingParent()
    {
        $url = '/api/reblog/' . $this->blogId . '/' . 10;
        $response = $this->post($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "The parent post was not found"
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving submissions with a non numeric blog id
     *
     * @return void
     */
    public function testGetSubmissionWithNonNumericBlogId()
    {
        $url = '/api/post/submission/' . '123sometextid';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The blog id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving submissions with a non existing blog
     *
     * @return void
     */
    public function testGetSubmissionWithNonExistingBlog()
    {
        $url = '/api/post/submission/' . '123';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "The Specified Blog id was not found"
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving profile posts of a non numeric blog id
     *
     * @return void
     */
    public function testGetProfilePostsWithNonNumericBlogId()
    {
        $url = '/api/posts/' . '123sometextid' . '/published';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The blog id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving profile posts of a non existing blog id
     *
     * @return void
     */
    public function testGetProfilePostsOfNonExistingBlogId()
    {
        $url = '/api/posts/' . '123' . '/published';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "This blog id is not found"
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving draft with a non numeric blog id
     *
     * @return void
     */
    public function testGetDraftPostsWithNonNumericBlogId()
    {
        $url = '/api/post/' . '123sometextid' . '/draft';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The blog Id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response message of retrieving draft with a non existing blog id
     *
     * @return void
     */
    public function testGetDraftPostsWithNonExistingBlogId()
    {
        $url = '/api/post/' . '123' . '/draft';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "This blog id is not found."
                ]
            ]);
    }
    /**
     * Testing the response message of creating submission with a non submission post status
     *
     * @return void
     */
    public function testCreateSubmissionWithNonSubmissionPost()
    {
        $requestBody = [
            'post_body' => 'hello',
            'post_status' => 'published',
            'post_type' => 'text'
        ];
        $url = '/api/post/submission/' . $this->blogId;
        $response = $this->json('POST', $url, $requestBody, Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "post_status should be 'submission'."
                ]
            ]);
    }
    /**
     * Testing the response of post creation route.
     *
     * @return void
     */
    public function testPostCreation()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        $requestBody = [
            "post_body" => '<h1>This is my new post</h1>',
            "post_status" => 'published',
            "post_type" => 'text',
            "post_time" => '2022-1-1'
        ];
        $url = '/api/post/' . $blog->id;
        $response = $this->json('POST', $url, $requestBody, Config::JSON);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "post_status" => "published",
                "post_time" => "2022-1-1",
                "post_type" => "text",
                "post_body" => "<h1>This is my new post</h1>",
                "blog_id" => strval($blog->id),
                "blog_username" => $blog->username,
            ]);
    }
    /**
     * Testing the response message of retrieving a post with a non-integer post id
     *
     * @return void
     */
    public function testNonNumericPostIdToRetrievPost()
    {
        $url = '/api/post/' . '123textid';
        $response = $this->get($url, [], Config::JSON)
            ->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The post id should be numeric."
                ]
            ]);
    }
    /**
     * Testing the response of getting a specific post that doesn't exist.
     *
     * @return void
     */
    public function testShowNonExistingPost()
    {
        $user = User::factory()->create();

        $url = '/api/post/100';
        $response = $this->json('GET', $url, [], Config::JSON);

        $response->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "This post was not found"
                ]
            ]);
    }
    /**
     * Testing the response of getting a specific post.
     *
     * @return void
     */
    public function testShowExistingPost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'blog_id' => $blog->id,
            'status' => 'published',
            'type' => 'general'
        ]);

        $url = '/api/post/' . $post->id;
        $response = $this->json('GET', $url, [], Config::JSON);

        $response->assertStatus(200)
            ->assertJson([
                "response" => [
                    "post_id" => $post->id,
                    "post_status" => $post->status,
                    "post_type" => $post->type,
                    "post_body" => $post->body,
                    "blog_id" => $blog->id,
                    "blog_username" => $blog->username
                ]
            ]);
    }
}
