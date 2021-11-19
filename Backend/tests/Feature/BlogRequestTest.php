<?php

namespace Tests\Feature;

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


        $blog = [
            "password" => "123",
            "title" => "First Blog"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog username field is required.",
            ]
        ]);
    }
    /**
     *  test Blog title is required
     *
     * @return void
     */
    public function testRequiredTitle()
    {

        $blog = [
            "blog_username" => "RadwaAhmed",
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title field is required.",
            ]
        ]);
    }
    /**
     *  test Blog username has at least 3 chars
     *
     * @return void
     */
    public function testSizeUsername()
    {
        $blog = [
            "blog_username" => "Ra",
            "title" => "Mytitle"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The blog username must be at least 3 characters.",
            ]
        ]);
    }
      /**
     *  test Blog title has at least 3 chars
     *
     * @return void
     */
    public function testSizeTitle()
    {

        $blog = [
            "blog_username" => "Radwa",
            "title" => "My"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title must be at least 3 characters.",
            ]
        ]);
    }
    /**
     *  test Blog password has at least 3 chars
     *
     * @return void
     */
    public function testSizePssword()
    {

        $blog = [
            "blog_username" => "Radwa",
            "title" => "MyBlog",
            "password" => "12"
        ];
        $response = $this
        ->json('POST', 'api/blog', $blog, ['Authorization' => 'Bearer ' . Config::TOKEN], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must be at least 3 characters.",
            ]
        ]);
    }
}
