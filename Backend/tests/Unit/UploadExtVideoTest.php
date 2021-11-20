<?php

namespace Tests\Unit;

use Tests\TestCase;

class UploadExtVideoTest extends TestCase
{
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
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "PleasePass^_^"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "https://bigfish.example.org/"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => "http://google.com/hello"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
        $videoArray = [
            'https://www.youtube.com/watch?v=jO7oTrcxEBA',
            'https://www.youtube.com/watch?v=cJRXnSkIFas',
            'https://www.facebook.com/watch?v=888513718748029',
        ];
        $response = $this->postJson('/api/upload_ext_video', [
            'videoUrl' => $videoArray,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
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
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(200);
    }
}
