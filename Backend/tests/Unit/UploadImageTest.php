<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    /**
     * unti test for uploading an image
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullImage()
    {
        Storage::fake('images');
        $response = $this->postJson('/api/upload_photo', [
            'image' => null,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The image field is required'
            ]
        ]);
    }

    /**
     * unti test for uploading an image
     * testing giving uploading one image of not supported type
     * Config::VALID_IMAGE_TYPES are the only type currently supported
     *
     * @return void
     */
    public function testUploadImageNotSupportedType()
    {
        Storage::fake('images');
        $notValidTypes = Config::NOT_VALID_IMAGE_TYPES;
        $randType = array_rand($notValidTypes, 1);
        $imageFile = UploadedFile::fake()
            ->image(
                name: Str::random(9) . '.' . $notValidTypes[$randType],
                width: mt_rand(50, 1024),
                height: mt_rand(50, 1024)
            )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
        $response = $this->postJson('/api/upload_photo', [
            'image' => $imageFile,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported image type',
            ]
        ]);
        Storage::disk('images')->assertMissing($imageFile->hashName());
    }

    /**
     * unti test for uploading an image
     * testing giving uploading one image with bigger size than Config::FILE_UPLOAD_MAX_SIZE
     *
     * @return void
     */

    public function testUploadImageBiggerSize()
    {
        Storage::fake('images');
        $validTypes = Config::VALID_IMAGE_TYPES;
        $randType = array_rand($validTypes, 1);
        $imageFile = UploadedFile::fake()
            ->image(
                name: Str::random(9) . '.' . $validTypes[$randType],
                width: mt_rand(50, 1024),
                height: mt_rand(50, 1024)
            )->size(mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE));
        $response = $this->postJson('/api/upload_photo', [
            'image' => $imageFile,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Allowed image max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
            ]
        ]);
        Storage::disk('images')->assertMissing($imageFile->hashName());
    }
    /**
     * unti test for uploading an image
     * testing giving uploading Array of valid images
     * currently this is not supoorted
     * should either upload the first one or all of them
     *
     * @return void
     */
    public function testUploadValidImageArray()
    {
        Storage::fake('images');
        $validTypes = Config::VALID_IMAGE_TYPES;
        $randType = array_rand($validTypes, 1);
        $numberOfImages = mt_rand(1, 10);
        $imageArray = [];
        for ($i =  0; $i < $numberOfImages; ++$i) {
            $imageFile = UploadedFile::fake()
                ->image(
                    name: Str::random(9) . '.' . $validTypes[$randType],
                    width: mt_rand(50, 1024),
                    height: mt_rand(50, 1024)
                )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
            array_push($imageArray, $imageFile);
        }
        $response = $this->postJson('/api/upload_photo', [
            'image' => $imageArray,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported image type'
            ]
        ]);
        Storage::disk('images')->assertMissing($imageArray[0]->hashName());
    }

    /**
     * unti test for uploading an image
     * testing giving uploading correct inputs
     *
     * @return void
     */
    public function testUploadValidImage()
    {
        Storage::fake('images');
        $validTypes = Config::VALID_IMAGE_TYPES;
        $randType = array_rand($validTypes, 1);
        $imageFile = UploadedFile::fake()
            ->image(
                name: Str::random(9) . '.' . $validTypes[$randType],
                width: mt_rand(50, 1024),
                height: mt_rand(50, 1024)
            )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
        $response = $this->postJson('/api/upload_photo', [
            'image' => $imageFile,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        $response->assertStatus(200);
        Storage::disk('images')->assertExists($imageFile->hashName());
    }
}
