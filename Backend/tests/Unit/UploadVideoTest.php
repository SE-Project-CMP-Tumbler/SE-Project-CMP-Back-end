<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadVideoTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * unti test for uploading an video
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullVideo()
    {
        Storage::fake('videos');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->postJson('/api/upload_video', [
            'video' => null,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The video field is required'
            ]
        ]);
    }

    /**
     * unti test for uploading an video
     * testing giving uploading one video of not supported type
     * Config::VALID_VIDEO_TYPES are the only type currently supported
     *
     * @return void
     */
    public function testUploadVideoNotSupportedType()
    {
        Storage::fake('videos');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        // get the first elements beceasue they're the ones that can be faked !
        $notValidTypes = array_slice(Config::NOT_VALID_VIDEO_TYPES, 0, Config::NOT_VALID_FAKE_LEN, true);
        $randType = array_rand($notValidTypes, 1);
        $videoFile = UploadedFile::fake()
            ->create(
                name: Str::random(9) . '.' . $notValidTypes[$randType],
                kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
            );
        $response = $this->postJson('/api/upload_video', [
            'video' => $videoFile,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported video type',
            ]
        ]);
        Storage::disk('videos')->assertMissing($videoFile->hashName());
    }

    /**
     * unti test for uploading an video
     * testing giving uploading one video with bigger size than Config::FILE_UPLOAD_MAX_SIZE
     *
     * @return void
     */
    public function testUploadvideoBiggerSize()
    {
        Storage::fake('videos');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $validTypes = array_slice(Config::VALID_VIDEO_TYPES, 0, Config::VALID_FAKE_LEN, true);
        $randType = array_rand($validTypes, 1);
        $videoFile = UploadedFile::fake()
            ->create(
                name: Str::random(9) . '.' . $validTypes[$randType],
                kilobytes: mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE)
            );
        $response = $this->postJson('/api/upload_video', [
            'video' => $videoFile,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Allowed video max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
            ]
        ]);
        Storage::disk('videos')->assertMissing($videoFile->hashName());
    }

    /**
     * unti test for uploading an video
     * testing giving uploading Array of valid videos
     * currently this is not supoorted
     * should either upload the first one or all of them
     *
     * @return void
     */
    public function testUploadValidvideoArray()
    {
        Storage::fake('videos');
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $validTypes = array_slice(Config::VALID_VIDEO_TYPES, 0, Config::VALID_FAKE_LEN, true);
        $randType = array_rand($validTypes, 1);
        $numberOfvideos = mt_rand(1, 10);
        $videoArray = [];
        for ($i =  0; $i < $numberOfvideos; ++$i) {
            $videoFile = UploadedFile::fake()
                ->create(
                    name: Str::random(9) . '.' . $validTypes[$randType],
                    kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
                );
            array_push($videoArray, $videoFile);
        }
        $response = $this->postJson('/api/upload_video', [
            'video' => $videoArray,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported video type'
            ]
        ]);
        Storage::disk('videos')->assertMissing($videoArray[0]->hashName());
    }

    /**
     * unti test for uploading an video
     * testing giving uploading correct inputs
     *
     * @return void
     */
    // public function testUploadValidvideo()
    // {
    //     Storage::fake('videos');
    //     $user = User::factory()->create();
    //     $token = $user->createToken('Auth Token')->accessToken;
    //     $validTypes = array_slice(Config::VALID_VIDEO_TYPES, 0, Config::VALID_FAKE_LEN, true);
    //     $randType = array_rand($validTypes, 1);
    //     $videoFile = new UploadedFile("/home/ahmed/Videos/anger.mp4", 'anger.mp4', null, null, true);
    //     $response = $this->postJson('/api/upload_video', [
    //         'video' => $videoFile,
    //     ], [
    //         'Content-Type' => 'application/json',
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . $token
    //     ]);
    //     $response->assertStatus(200);
    //     Storage::disk('videos')->assertExists($videoFile->hashName());
    // }
}
