<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UploadExtVideoTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * unti test for uploading an video through external url
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullVideoUrl()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => null,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "PleasePass^_^"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "https://bigfish.example.org/"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "http://google.com/hello"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $videoArray = [
            'https://www.youtube.com/watch?v=jO7oTrcxEBA',
            'https://www.youtube.com/watch?v=cJRXnSkIFas',
            'https://www.facebook.com/watch?v=888513718748029',
        ];
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => $videoArray,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $videoArray = [
            'https://www.youtube.com/watch?v=jO7oTrcxEBA',
            'https://www.youtube.com/watch?v=cJRXnSkIFas',
            'https://www.facebook.com/watch?v=888513718748029',
        ];
        $randVideo = array_rand($videoArray, 1);
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => $videoArray[$randVideo]
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }
}
