<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadImageBase64Test extends TestCase
{
    // use RefreshDatabase;

    protected $storageDriver = 'public';

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
        $response = $this->postJson('/api/upload_base64_photo', [
            'b64_image' => null,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    /**
     * unti test for uploading an image
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullB64Image()
    {
        Storage::fake($this->storageDriver);
        $response = $this->postJson('/api/upload_base64_photo', [
            'b64_image' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The b64 image field is required.'
            ]
        ]);
    }

    // public function testUploadBadBase64Image()
    // {
    //     Storage::fake($this->storageDriver);
    //     $data = Str::random(500);
    //     $b64Image = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_photo', [
    //         'b64_image' => $b64Image,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     $response->assertStatus(422);
    //     // Storage::disk($this->storageDriver)->assertMissing($imageFile->hashName());
    // }

    /**
     * unti test for uploading an image
     * testing giving uploading one image of not supported type
     * Config::VALID_IMAGE_TYPES are the only type currently supported
     *
     * @return void
     */
    // public function testUploadB64ImageNotSupportedType()
    // {
    //     Storage::fake($this->storageDriver);
    //     $notValidTypes = Config::NOT_VALID_IMAGE_TYPES;
    //     $randType = array_rand($notValidTypes, 1);
    //     $imageFile = UploadedFile::fake()
    //         ->image(
    //             name: Str::random(9) . '.' . $notValidTypes[$randType],
    //             width: mt_rand(50, 1024),
    //             height: mt_rand(50, 1024)
    //         )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
    //     $data = file_get_contents($imageFile);
    //     $b64Image = base64_encode($data);
    //     // dd($b64Image);
    //     $response = $this->postJson('/api/upload_base64_photo', [
    //         'b64_image' => $b64Image,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     $response->assertStatus(422);
    //     $response->assertJson([
    //         'meta' => [
    //             'status' => '422',
    //             'msg' => 'unsupported type',
    //         ]
    //     ]);
    //     Storage::disk($this->storageDriver)->assertMissing($imageFile->hashName());
    // }

    /**
     * unti test for uploading an image
     * testing giving uploading one image with bigger size than Config::FILE_UPLOAD_MAX_SIZE
     *
     * @return void
     */

    // public function testUploadImageBiggerSize()
    // {
    //     Storage::fake($this->storageDriver);
    //     $validTypes = Config::VALID_IMAGE_TYPES;
    //     $randType = array_rand($validTypes, 1);
    //     $imageFile = UploadedFile::fake()
    //         ->image(
    //             name: Str::random(9) . '.' . $validTypes[$randType],
    //             width: mt_rand(50, 1024),
    //             height: mt_rand(50, 1024)
    //         )->size(mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE));
    //     $data = file_get_contents($imageFile);
    //     $b64Image = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_photo', [
    //         'b64_image' => $b64Image,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     $response->assertStatus(422);
    //     $response->assertJson([
    //         'meta' => [
    //             'status' => '422',
    //             'msg' => 'bad file size'
    //         ]
    //     ]);
    //     Storage::disk($this->storageDriver)->assertMissing($imageFile->hashName());
    // }

    /**
     * unti test for uploading an image
     * testing giving uploading correct inputs
     *
     * @return void
     */
    public function testUploadValidB64Image()
    {
        Storage::fake($this->storageDriver);
        $validTypes = Config::VALID_IMAGE_TYPES;
        $randType = array_rand($validTypes, 1);
        $imageFile = UploadedFile::fake()
            ->image(
                name: Str::random(9) . '.' . $validTypes[$randType],
                width: mt_rand(50, 1024),
                height: mt_rand(50, 1024)
            )->size(mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE));
        $data = file_get_contents($imageFile);
        $b64Image = base64_encode($data);
        $response = $this->postJson('/api/upload_base64_photo', [
            'b64_image' => $b64Image,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(200);
        $uploadedImagePath = explode('/', $response->getOriginalContent()["response"]["url"]);
        $len = count($uploadedImagePath);

        $uploadedImageName = '';
        if ($len > 2) {
            $uploadedImageName = $uploadedImagePath[$len - 2] . '/' . $uploadedImagePath[$len - 1];
        }
        Storage::disk($this->storageDriver)->assertExists($uploadedImageName);
    }
}
