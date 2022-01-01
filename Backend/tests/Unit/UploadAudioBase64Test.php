<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadAudioBase64Test extends TestCase
{
    use RefreshDatabase;

    protected $storageDriver = 'ftp';

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
        $response = $this->postJson('/api/upload_base64_audio', [
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
    public function testUploadNullB64Audio()
    {
        Storage::fake($this->storageDriver);
        $response = $this->postJson('/api/upload_base64_audio', [
            'b64_audio' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The b64 audio field is required.'
            ]
        ]);
    }

    // public function testUploadBadBase64Audio()
    // {
    //     Storage::fake($this->storageDriver);
    //     $data = Str::random(500);
    //     $b64Audio = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_audio', [
    //         'b64_audio' => $b64Audio,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     $response->assertStatus(422);
    //     // Storage::disk($this->storageDriver)->assertMissing($imageFile->hashName());
    // }

    // public function testUploadB64AudioNotSupportedType()
    // {
    //     Storage::fake($this->storageDriver);
    //     $notValidTypes = Config::NOT_VALID_AUDIO_TYPES;
    //     $randType = array_rand($notValidTypes, 1);
    //     $audioFile = UploadedFile::fake()
    //         ->create(
    //             name: Str::random(9) . '.' . $notValidTypes[$randType],
    //             kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
    //         );
    //     $data = file_get_contents($audioFile);
    //     $b64Image = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_audio', [
    //         'b64_audio' => $b64Image,
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
    //     Storage::disk($this->storageDriver)->assertMissing($audioFile->hashName());
    // }

    // public function testUploadAudioBiggerSize()
    // {
    //     Storage::fake($this->storageDriver);
    //     $validTypes = Config::VALID_AUDIO_TYPES;
    //     $randType = array_rand($validTypes, 1);
    //     $audioFile = UploadedFile::fake()
    //         ->create(
    //             name: Str::random(9) . '.' . $validTypes[$randType],
    //             kilobytes: mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE)
    //         );
    //     $data = file_get_contents($audioFile);
    //     $b64Image = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_audio', [
    //         'b64_audio' => $b64Image,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     $response->assertStatus(422);
    //     $response->assertJson([
    //         'meta' => [
    //             'status' => '422',
    //             'msg' => 'Allowed audio max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
    //         ]
    //     ]);
    //     Storage::disk($this->storageDriver)->assertMissing($audioFile->hashName());
    // }

    // public function testUploadValidB64Audio()
    // {
    //     Storage::fake($this->storageDriver);
    //     $validTypes = Config::VALID_AUDIO_TYPES;
    //     $randType = array_rand($validTypes, 1);
    //     $audioFile = '/home/ahmed/Music/y2mate.com - ANGRY DEXTER next to me shorts.mp3';
    //     $data = file_get_contents($audioFile);
    //     $b64Audio = base64_encode($data);
    //     $response = $this->postJson('/api/upload_base64_audio', [
    //         'b64_audio' => $b64Audio,
    //     ], array_merge(Config::JSON, [
    //         'Authorization' => 'Bearer ' . $this->accessToken,
    //     ]));
    //     dd($response);
    //     $response->assertStatus(200);

    //     $uploadedAudioPath = explode('/', $response->getOriginalContent()["response"]["url"]);
    //     $len = count($uploadedAudioPath);

    //     $uploadedAudioName = '';
    //     if ($len > 2) {
    //         $uploadedAudioName = $uploadedAudioPath[$len - 2] . '/' . $uploadedAudioPath[$len - 1];
    //     }
    //     Storage::disk($this->storageDriver)->assertExists($uploadedAudioName);
    // }
}
