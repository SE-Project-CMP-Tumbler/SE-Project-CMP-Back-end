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
            "title" => "First Blog"
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
}
