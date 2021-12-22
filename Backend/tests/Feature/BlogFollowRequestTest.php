<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Blog;
use App\Models\FollowBlog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Tests\TestCase;

class BlogFollowRequestTest extends TestCase
{
     /**
     *  test Blog value is required
     *
     * @return void
     */

    public function testRequiredBlogValue()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [];
        $response = $this
        ->json('POST', 'api/follow_blog_search', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog value field is required.",
            ]
        ]);
    }
    /**
     *  test Blog value and follow him self
     *
     * @return void
     */

    public function testTrueBlogValue()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $token = $user->createToken('Auth Token')->accessToken;
        $username = $blog->username;
        $newBlog = [
            "blog_value" => $blog->username
        ];
        $response = $this
        ->json('POST', 'api/follow_blog_search', $newBlog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "You can not follow your self"
            ]
        ]);
    }
     /**
     *  follow another blog by search
     *
     * @return void
     */

    public function testFolowBlogValue()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $username = $anotherBlog->username;
        $newBlog = [
            "blog_value" => $username
        ];
        $response = $this
        ->json('POST', 'api/follow_blog_search', $newBlog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ]
        ]);
    }
     /**
     * test true follow another blog
     *
     * @return void
     */

    public function testTrueFolowBlog()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/follow_blog/' . $anotherBlog->id;
        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('POST', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ]
        ]);
    }
     /**
     * test unfollow another blog
     *
     * @return void
     */

    public function testTrueUnfolowBlog()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $followblog = FollowBlog::factory()->create([
            'follower_id' => $blog->id,
            'followed_id' => $anotherBlog->id
        ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/follow_blog/' . $anotherBlog->id;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('delete', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ]
        ]);
    }
     /**
     * test check is followed by 
     *
     * @return void
     */

    public function testCheckIsFollowedFalse()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/followed_by/' . $anotherBlog->id;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('get', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ],
            "response" => [
                "followed" => false
            ]
        ]);
    }
     /**
     * test check is followed by 
     *
     * @return void
     */

    public function testCheckIsFollowedTrue()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $followblog = FollowBlog::factory()->create([
            'follower_id' => $blog->id,
            'followed_id' => $anotherBlog->id
        ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/followed_by/' . $anotherBlog->id;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('get', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ],
            "response" => [
                "followed" => true
            ]
        ]);
    }
    /**
     * test total followers
     *
     * @return void
     */

    public function testTotalFollowers()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $followblog = FollowBlog::factory()->create([
            'follower_id' => $blog->id,
            'followed_id' => $anotherBlog->id
        ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/total_followers/' . $anotherBlog->id;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('get', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ],
            "response" => [
                "followers" => 1
            ]
        ]);
    }
    /**
     * test total followings
     *
     * @return void
     */

    public function testTotalFollowings()
    {

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherUser = User::factory()->create();
        $anotherBlog = Blog::factory()->create(['user_id' => $anotherUser->id ]);
        $followblog = FollowBlog::factory()->create([
            'follower_id' => $blog->id,
            'followed_id' => $anotherBlog->id
        ]);
        $token = $user->createToken('Auth Token')->accessToken;
        $url = 'api/total_followings/' . $blog->id;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('get', $url, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ],
            "response" => [
                "followings" => 1
            ]
        ]);
    }
}
