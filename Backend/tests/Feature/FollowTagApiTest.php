<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\BlogFollowTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FollowTagApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing response when following a tag via an unathenticated guest.
     *
     * @return void
     */
    public function testUnathenticatedGuestCanNotFollowTagResponse()
    {
        $response = $this->post('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(401)
            ->assertJson([
                "meta" => [
                    "status" => "401",
                    "msg" => "Unauthorized."
                ]
            ]);
    }
    /**
     * Testing response when an athenticated user follows a non existing tag.
     *
     * @return void
     */
    public function testUserFollowNonExistingTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        $response = $this->post('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "A Tag with the specified id was not found"
                ]
            ]);
    }
    /**
     * Testing the response of following a tag that the user already follows
     *
     * @return void
     */
    public function testUserFollowAlreadyFollowedTag()
    {
        //Create the testing user
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);

        //Create the tag and the follow relation between the user and the tag
        Tag::factory()->create(['description' => 'scrum']);
        BlogFollowTag::create(['tag_description' => 'scrum', 'blog_id' => $blog->id]);
        $response = $this->post('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The blog already follows this tag!"
                ]
            ]);
    }
    /**
     * Testing response when an athenticated user successfully follows a tag.
     *
     * @return void
     */
    public function testUserHaveFollowedTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        Tag::factory()->create(['description' => 'scrum']);
        $response = $this->post('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(200)
            ->assertJson([
                "meta" => [
                    "status" => "200",
                    "msg" => "OK"
                ]
            ]);
    }
    /**
     * Testing response when unfollowing a tag via an unathenticated guest.
     *
     * @return void
     */
    public function testUnathenticatedGuestCanNotUnFollowTagResponse()
    {
        $response = $this->delete('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(401)
            ->assertJson([
                "meta" => [
                    "status" => "401",
                    "msg" => "Unauthorized."
                ]
            ]);
    }
    /**
     * Testing response when an athenticated user follows a non existing tag.
     *
     * @return void
     */
    public function testUserUnFollowNonExistingTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        $response = $this->delete('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(404)
            ->assertJson([
                "meta" => [
                    "status" => "404",
                    "msg" => "A Tag with the specified id was not found"
                ]
            ]);
    }
    /**
     * Testing response when an athenticated user unfollows a not following tag.
     *
     * @return void
     */
    public function testUserUnFollowNotFollowedTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        Tag::factory()->create(['description' => 'scrum']);
        $response = $this->delete('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(422)
            ->assertJson([
                "meta" => [
                    "status" => "422",
                    "msg" => "The Blog isn't already following this tag!"
                ]
            ]);
    }
    /**
     * Testing response when an athenticated user successfully unfollows a tag.
     *
     * @return void
     */
    public function testUserHaveUnFollowedTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        Tag::factory()->create(['description' => 'scrum']);
        BlogFollowTag::create(['tag_description' => 'scrum', 'blog_id' => $blog->id]);
        $response = $this->delete('/api/follow_tag/scrum', [], Config::JSON);

        $response->assertStatus(200)
            ->assertJson([
                "meta" => [
                    "status" => "200",
                    "msg" => "OK"
                ]
            ]);
    }
    /**
     * Testing response of a user checking the follow status with a not followed tag.
     *
     * @return void
     */
    public function testFollowStatusOfUserNotFollowingTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        Tag::factory()->create(['description' => 'scrum']);
        $response = $this->get('/api/tag/is_following/scrum', [], Config::JSON);

        $response->assertStatus(200)
            ->assertJson([
                "response" => [
                    "is_following" => false
                ]
            ]);
    }
    /**
     * Testing response of a user checking the follow status with a followed tag.
     *
     * @return void
     */
    public function testFollowStatusOfUserFollowingTagResponse()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        Passport::actingAs($user);
        Tag::factory()->create(['description' => 'scrum']);
        BlogFollowTag::create(['tag_description' => 'scrum', 'blog_id' => $blog->id]);
        $response = $this->get('/api/tag/is_following/scrum', [], Config::JSON);

        $response->assertStatus(200)
            ->assertJson([
                "response" => [
                    "is_following" => true
                ]
            ]);
    }
}
