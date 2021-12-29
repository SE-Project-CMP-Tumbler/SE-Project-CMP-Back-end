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
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "response" => [
                "theme-id" => $theme->id,
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
                    "shape" => "suq"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The selected avatar.0.shape is invalid."
            ]
        ]);
    }
     /**
     *  test Invalid  description Theme  of blog
     *
     * @return void
     */

    public function testInvalidThemeDescription()
    {

        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $theme = Theme::factory()->create(['blog_id' => $blog->id ]);
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
                    "text" => ""
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
                    "shape" => "sqaure"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The description.0.text must be a string."
            ]
        ]);
    }
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

                "title" => [
                  [
                    "text" => "rwwdddd",
                    "color" => "colortitle",
                    "font" => "fonttitle #",
                    "font_weight" => "fontweighttitle"
                   ]
                ],
                "description" => [
                  [
                    "text" => ""
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
                    "shape" => "sqaure"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title.0.font format is invalid."
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

                "title" => [
                  [
                    "text" => "rwwdddd",
                    "color" => "colortitle",
                    "font" => "fonttitle ",
                    "font_weight" => "fontweighttitle #"
                   ]
                ],
                "description" => [
                  [
                    "text" => ""
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
                    "shape" => "sqaure"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The title.0.font_weight format is invalid."
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

                "title" => [
                  [
                    "text" => "rwwdddd",
                    "color" => "colortitle",
                    "font" => "fonttitle ",
                    "font_weight" => "fontweighttitle"
                   ]
                ],
                "description" => [
                  [
                    "text" => "wwww"
                  ]
                ],
                "background_color" => "backgroundcolor",
                "accent_color" => "#ee55",
                "body_font" => "bodyfont",
                "header_image" => [
                  [
                    "url" => "www"
                   ]
                ],
                "avatar" => [
                  [
                    "url" => "https://media.dev.tumbler.social/xXlTveKWWHKZg163eSaDlk19rSo4FlAKP3hTUtL4/7RnFSw6P8GcpipuouNb8zNZ7qxM9MLTTayEb3zhL.png",
                    "shape" => "sqaure"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The header_image.0.url must be a valid URL."
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

                "title" => [
                  [
                    "text" => "rwwdddd",
                    "color" => "colortitle",
                    "font" => "fonttitle ",
                    "font_weight" => "fontweighttitle"
                   ]
                ],
                "description" => [
                  [
                    "text" => "wwww"
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
                    "url" => "jjx",
                    "shape" => "sqaure"
                  ]
                ]
        ];
        $response = $this
        ->json('PUT', 'api/blog/' . $blog->id . '/theme', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The avatar.0.url must be a valid URL."
            ]
        ]);
    }
}
