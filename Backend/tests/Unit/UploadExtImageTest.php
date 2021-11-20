<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadExtImageTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * unti test for uploading an image through external url
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullImageUrl()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => null,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The image url field is required'
            ]
        ]);
    }

    /**
     * unti test for uploading an image through external url
     * testing giving invalid url
     *
     * @return void
     */
    public function testUploadInvalidImageUrl()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => "PleasePass^_^"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'image url should be valid image url'
            ]
        ]);
    }

    /**
     * unti test for uploading an image through external url
     * testing giving giving valid url but not working
     *
     * @return void
     */
    public function testUploadValidButNotWorkingImageUrl()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => "https://bigfish.example.org/"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'image url should be valid image url'
            ]
        ]);
    }

    /**
     * unti test for uploading an image through external url
     * testing giving giving valid working url doesn't return 200
     *
     * @return void
     */
    public function testUploadValidWorkingImageUrlNotOk()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => "http://google.com/hello"
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'image url should be valid image url'
            ]
        ]);
    }

    /**
     * unti test for uploading an image through external url
     * testing giving uploading one image of not supported type
     * Config::VALID_IMAGE_TYPES are the only type currently supported
     *
     * @return void
     */
    public function testUploadExtImageNotSupportedType()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => 'https://media.flaticon.com/dist/min/img/logo/flaticon_negative.svg',
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => "The image type isn't supported",
            ]
        ]);
        // Storage::disk('images')->assertMissing($imageFile->hashName());
    }

    /**
     * unti test for uploading an image through external url
     * testing giving uploading one image with bigger size than Config::FILE_UPLOAD_MAX_SIZE
     *
     * @return void
     */
    // public function testUploadExtImageBiggerSize()
    // {
    //     // Storage::fake('images');
    //     $user = User::factory()->create();
    //     $token = $user->createToken('Auth Token')->accessToken;
    //     $validTypes = Config::VALID_IMAGE_TYPES;
    //     $randType = array_rand($validTypes, 1);
    //     $imageOriginalName = Str::random(9) . '.' . $validTypes[$randType];
    //     $imageFile = UploadedFile::fake()
    //         ->image(
    //             name: $imageOriginalName,
    //             width: mt_rand(50, 1024),
    //             height: mt_rand(50, 1024)
    //         )->size(mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE));
    //     $imageFile->store('', 'images');
    //     Storage::disk('images')->assertExists($imageFile->hashName());
    //     $response = $this->postJson('/api/upload_ext_photo', [
    //         'imageUrl' => 'http://127.0.0.1:8000/storage/images/' . $imageFile->hashName(),
    //     ], [
    //         'Content-Type' => 'application/json',
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . $token,
    //     ]);
    //     $response->assertStatus(422);
    //     $response->assertJson([
    //         'meta' => [
    //             'status' => '422',
    //             'msg' => 'Allowed image max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
    //         ]
    //     ]);
    //     Storage::disk('images')->delete($imageFile->hashName());
    //     Storage::disk('images')->assertMissing($imageFile->hashName());
    // }

    /**
     * unti test for uploading an image through external url
     * testing giving uploading Array of valid images
     * currently this is not supoorted
     * should either upload the first one or all of them
     *
     * @return void
     */
    // public function testUploadValidExtImageArray()
    // {
    //     Storage::fake('images');
    //     $user = User::factory()->create();
    //     $token = $user->createToken('Auth Token')->accessToken;
    //     $validTypes = Config::VALID_IMAGE_TYPES;
    //     $randType = array_rand($validTypes, 1);
    //     $numberOfImages = mt_rand(1, 10);
    //     $imageArray = [];
    //     for ($i =  0; $i < $numberOfImages; ++$i) {
    //         $imageOriginalName = Str::random(9) . '.' . $validTypes[$randType];
    //         $imageFile = UploadedFile::fake()
    //             ->image(
    //                 name: $imageOriginalName,
    //                 width: mt_rand(50, 1024),
    //                 height: mt_rand(50, 1024)
    //             )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
    //         $imageFile->store('', 'images');
    //         Storage::disk('images')->assertExists($imageFile->hashName());
    //         array_push($imageArray, $imageFile);
    //     }
    //     $response = $this->postJson('/api/upload_ext_photo', [
    //         'image' => $imageArray,
    //     ], [
    //         'Content-Type' => 'application/json',
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . $token,
    //     ]);
    //     $response->assertStatus(422);
    //     $response->assertJson([
    //         'meta' => [
    //             'status' => '422',
    //             'msg' => 'imageUrl should be valid image url'
    //         ]
    //     ]);
    //     for ($i =  0; $i < $numberOfImages; ++$i) {
    //         Storage::disk('images')->delete($imageFile->hashName());
    //         Storage::disk('images')->assertMissing($imageFile->hashName());
    //     }
    // }

    /**
     * unti test for uploading an image through external url
     * testing giving valid ext image url
     *
     * @return void
     */
    public function testUploadValidExtImage()
    {
        Storage::fake('images');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_ext_photo', [
            'imageUrl' => 'https://64.media.tumblr.com/a4cd582dda1f8acdfbc5b5f031de930c/a4850b3a923b10a4-08/s400x600/1c5c0d98d1f2cfc49ce37dfe333a5270b67f7b48.jpg',
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
        $uploadedImageName = basename($response->getOriginalContent()["response"]["url"]);
        Storage::disk('images')->assertExists($uploadedImageName);
    }
}
