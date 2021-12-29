<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Blog;
use App\Models\FollowBlog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Tests\TestCase;

class ThemeRequestTest extends TestCase
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
        $blog = Blog::factory()->create();
        $request = [

                "title" => [
                  [
                    "text" => "rwwdddd",
                    "color" => "colortitle",
                    "font" => "fonttitle",
                    "font_weight" => "fontweighttitle"
                   ]
                ],
                "description" => [
                  [
                    "text" => "slll"
                  ]
                ],
                "background_color" => "backgroundcolor",
                "accent_color" => "#ee55",
                "body_font" => "bodyfont",
                "header_image" => [
                  [
                    "url" => "https://media.dev.tumbler.social/xXlTveKWWHKZg163eSaDlk19rSo4FlAKP3hTUtL4/7RnFSw6P8GcpipuouNb8zNZ7qxM9MLTTayEb3zhL.png"
                   ]
                ],
                "avatar" => [
                  [
                    "url" => "https://media.dev.tumbler.social/xXlTveKWWHKZg163eSaDlk19rSo4FlAKP3hTUtL4/7RnFSw6P8GcpipuouNb8zNZ7qxM9MLTTayEb3zhL.png",
                    "shape" => "circle"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api//blog/' . $blog->id . '/theme', $blog, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "response" => [
                "theme-id" => 6,
                "title" => [
                    [
                        "text" => "rwwdddd",
                        "color" => "colortitle",
                        "font" => "fonttitle",
                        "font_weight" => "fontweighttitle"
                    ]
                ],
                "description" => [
                    [
                        "text" => "slll"
                    ]
                ],
                "background_color" => "backgroundcolor",
                "accent_color" => "#e17e66",
                "body_font" => "bodyfont",
                "header_image" => [
                    [
                        "url" => "https://media.dev.tumbler.social/xXlTveKWWHKZg163eSaDlk19rSo4FlAKP3hTUtL4/7RnFSw6P8GcpipuouNb8zNZ7qxM9MLTTayEb3zhL.png"
                    ]
                ],
                "avatar" => [
                    [
                        "url" => "https://media.dev.tumbler.social/xXlTveKWWHKZg163eSaDlk19rSo4FlAKP3hTUtL4/7RnFSw6P8GcpipuouNb8zNZ7qxM9MLTTayEb3zhL.png",
                        "shape" => "circle"
                    ]
                ]
            ],
            "meta" => [
                "status" => "200",
                "msg" => "ok"
            ]
        ]);
    }
}
