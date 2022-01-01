<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Blog;
use App\Models\Theme;
use App\Models\FollowBlog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Tests\TestCase;

class ThemeRequestTest extends TestCase
{
    use RefreshDatabase;

     /**
     *  test True Theme of blog
     *
     * @return void
     */

    public function testTrueTheme()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "color_title" => "#000000"
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "response" => [
              "theme-id" => $theme->id,
              "color_title" => "#000000",
              "font_title" => $theme->font_title,
              "font_weight_title" => $theme->font_weight_title,
              "description" => $blog->description,
              "title" => $blog->title,
              "background_color" => $theme->background_color,
              "accent_color" => $theme->accent_color,
              "body_font" => $theme->body_font,
              "header_image" => $blog->header_image,
              "avatar" => $blog->avatar,
              "avatar_shape" => $blog->avatar_shape
            ],
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ]
        ]);
    }
     /**
     *  test Invalid shape Theme of blog
     *
     * @return void
     */

    public function testInvalidThemeShape()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "avatar_shape" => "circl"
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected avatar shape is invalid."
            ]
        ]);
    }
    //  /**
    //  *  test Invalid  description Theme  of blog
    //  *
    //  * @return void
    //  */

    // public function testInvalidThemeDescription()
    // {

    //     $user = User::factory()->create();
    //     $token = $user->createToken('Auth Token')->accessToken;
    //     $blog = Blog::factory()->create(['user_id' => $user->id]);
    //     $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
    //     $request = [
    //       "description" => ""
    //     ];
    //     $response = $this
    //     ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
    //     ->assertJson([
    //         "meta" => [
    //             "status" => "422",
    //             "msg" => "The description must be a string."
    //         ]
    //     ]);
    // }
     /**
     *  test Invalid  font Theme  of blog
     *
     * @return void
     */

    public function testInvalidThemeFont()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "font_title" => ""
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The font title must be a string."
            ]
        ]);
    }
    /**
     *  test Invalid  font weight Theme  of blog
     *
     * @return void
     */

    public function testInvalidThemeFontWight()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "font_weight_title" => "bolde 3#"
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The font weight title format is invalid."
            ]
        ]);
    }
     /**
     *  test Invalid  header image  of blog
     *
     * @return void
     */

    public function testInvalidThemeHeaderImage()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "header_image" => "www.image.com"
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The header image must be a valid URL."
            ]
        ]);
    }
     /**
     *  test Invalid avatar of blog
     *
     * @return void
     */

    public function testInvalidThemeAvatar()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
        $request = [
          "avatar" => "www.jjj.com"
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The avatar must be a valid URL."
            ]
        ]);
    }
}
