<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  test Blog username is required
     *
     * @return void
     */

    public function testRequiredUsername()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [
            "password" => "123",
            "title" => "FirstBlog"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog username field is required.",
            ]
        ]);
        $user->delete();
    }
    /**
     *  test Blog title is required
     *
     * @return void
     */
    public function testRequiredTitle()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [
            "blog_username" => "RadwaAhmed",
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title field is required.",
            ]
        ]);
        $user->delete();
    }
    /**
     *  test Blog username has at least 3 chars
     *
     * @return void
     */
    public function testSizeUsername()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [
            "blog_username" => "Ra",
            "title" => "Mytitle"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog username must be at least 3 characters.",
            ]
        ]);
        $user->delete();
    }
      /**
     *  test Blog title has at least 3 chars
     *
     * @return void
     */
    public function testSizeTitle()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [
            "blog_username" => "Radwa",
            "title" => "My"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title must be at least 3 characters.",
            ]
        ]);
        $user->delete();
    }
    /**
     *  test Blog password has at least 3 chars
     *
     * @return void
     */
    public function testSizePssword()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = [
            "blog_username" => "Radwa",
            "title" => "MyBlog",
            "password" => "12"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must be at least 3 characters.",
            ]
        ]);
        $user->delete();
    }
     /**
     *  test delete  blog
     *
     * @return void
     */
    public function testDeleteBlog()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $anotherBlog = Blog::factory()->create(['user_id' => $user->id ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('delete', 'api/blog/' . $anotherBlog->id, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "ok",
            ]
        ]);
        $user->delete();
    }
     /**
     *  test delete primary blog
     *
     * @return void
     */
    public function testDeletePrimaryBlog()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id ,'is_primary' => true]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('delete', 'api/blog/' . $blog->id, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "Can't delete this blog because this is primary",
            ]
        ]);
        $user->delete();
    }
}
