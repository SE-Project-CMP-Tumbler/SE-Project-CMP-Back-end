<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UploadExtVideoTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * The access token of the authenticated user that would do testing operations
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Require the access token for the testing user before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->accessToken = $user->createToken('Auth Token')->accessToken;
    }

    public function testUnauthorizedRequest()
    {
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => null,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    /**
     * unti test for uploading an video through external url
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullVideoUrl()
    {
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The video url field is required'
            ]
        ]);
    }

    /**
     * unti test for uploading an video through external url
     * testing giving invalid url
     *
     * @return void
     */
    public function testUploadInValidVideoUrl()
    {
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "PleasePass^_^"
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'video url should be a valid video url'
            ]
        ]);
    }

    /**
     * unti test for uploading an video through external url
     * testing giving giving valid url but not working
     *
     * @return void
     */
    public function testUploadValidButNotWorkingVideoUrl()
    {
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "https://bigfish.example.org/"
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'video url should be valid video url'
            ]
        ]);
    }

    /**
     * unti test for uploading an video through external url
     * testing giving giving valid working url doesn't return 200
     *
     * @return void
     */
    public function testUploadValidWorkingVideoUrlNotOk()
    {
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "http://google.com/hello"
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'video url should be valid video url'
            ]
        ]);
    }

    /**
     * unti test for uploading an video through external url
     * testing giving uploading Array of valid videos
     * currently this is not supoorted
     * should either upload the first one or all of them
     *
     * @return void
     */
    public function testUploadValidExtVideoArray()
    {
        $videoArray = [
            'https://www.youtube.com/watch?v=jO7oTrcxEBA',
            'https://www.youtube.com/watch?v=cJRXnSkIFas',
            'https://www.facebook.com/watch?v=888513718748029',
        ];
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => $videoArray,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'video url should be a valid video url'
            ]
        ]);
    }

    /**
     * unti test for uploading an video through external url
     * testing giving valid ext video url
     *
     * @return void
     */
    public function testUploadValidExtvideo()
    {
        $videoArray = [
            'https://www.youtube.com/watch?v=jO7oTrcxEBA',
            'https://www.youtube.com/watch?v=cJRXnSkIFas',
            'https://www.facebook.com/watch?v=888513718748029',
        ];
        $randVideo = array_rand($videoArray, 1);
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => $videoArray[$randVideo]
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(200);
    }
}
