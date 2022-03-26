<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadAudioTest extends TestCase
{
    use RefreshDatabase;

    protected $storageDriver;

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
        $this->storageDriver = env('STORAGE_DRIVE', 'public');
    }

    public function testUnauthorizedRequest()
    {
        $response = $this->postJson('/api/upload_audio', [
            'audio' => null,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    /**
     * unti test for uploading an audio
     * testing giving uploading null
     *
     * @return void
     */
    public function testUploadNullAudio()
    {
        Storage::fake($this->storageDriver);
        $response = $this->postJson('/api/upload_audio', [
            'audio' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The audio field is required'
            ]
        ]);
    }

    /**
     * unti test for uploading an audio
     * testing giving uploading one audio of not supported type
     * Config::VALID_AUDIO_TYPES are the only type currently supported
     *
     * @return void
     */
    public function testUploadAudioNotSupportedType()
    {
        Storage::fake($this->storageDriver);
        $notValidTypes = Config::NOT_VALID_AUDIO_TYPES;
        $randType = array_rand($notValidTypes, 1);
        $audioFile = UploadedFile::fake()
            ->create(
                name: Str::random(9) . '.' . $notValidTypes[$randType],
                kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
            );
        $response = $this->postJson('/api/upload_audio', [
            'audio' => $audioFile,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported audio type',
            ]
        ]);
        Storage::disk($this->storageDriver)->assertMissing($audioFile->hashName());
    }

    /**
     * unti test for uploading an audio
     * testing giving uploading one audio with bigger size than Config::FILE_UPLOAD_MAX_SIZE
     *
     * @return void
     */

    public function testUploadAudioBiggerSize()
    {
        Storage::fake($this->storageDriver);
        $validTypes = Config::VALID_AUDIO_TYPES;
        $randType = array_rand($validTypes, 1);
        $audioFile = UploadedFile::fake()
            ->create(
                name: Str::random(9) . '.' . $validTypes[$randType],
                kilobytes: mt_rand(Config::FILE_UPLOAD_MAX_SIZE + 1, 2 * Config::FILE_UPLOAD_MAX_SIZE)
            );
        $response = $this->postJson('/api/upload_audio', [
            'audio' => $audioFile,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Allowed audio max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
            ]
        ]);
        Storage::disk($this->storageDriver)->assertMissing($audioFile->hashName());
    }

    /**
     * unti test for uploading an audio
     * testing giving uploading Array of valid audios
     * currently this is not supoorted
     * should either upload the first one or all of them
     *
     * @return void
     */
    public function testUploadValidAudioArray()
    {
        Storage::fake($this->storageDriver);
        $validTypes = Config::VALID_AUDIO_TYPES;
        $randType = array_rand($validTypes, 1);
        $numberOfAudios = mt_rand(1, 10);
        $audioArray = [];
        for ($i =  0; $i < $numberOfAudios; ++$i) {
            $audioFile = UploadedFile::fake()
                ->create(
                    name: Str::random(9) . '.' . $validTypes[$randType],
                    kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
                );
            array_push($audioArray, $audioFile);
        }
        $response = $this->postJson('/api/upload_audio', [
            'audio' => $audioArray,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Not supported audio type'
            ]
        ]);
        Storage::disk($this->storageDriver)->assertMissing($audioArray[0]->hashName());
    }

    /**
     * unti test for uploading an audio
     * testing giving uploading correct inputs
     *
     * @return void
     */
    public function testUploadValidAudio()
    {
        Storage::fake($this->storageDriver);
        $validTypes = Config::VALID_AUDIO_TYPES;
        $randType = array_rand($validTypes, 1);
        $audioFile = UploadedFile::fake()
            ->create(
                name: Str::random(9) . '.' . $validTypes[$randType],
                kilobytes: mt_rand(10, Config::FILE_UPLOAD_MAX_SIZE)
            );
        $response = $this->postJson('/api/upload_audio', [
            'audio' => $audioFile,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ]));
        $response->assertStatus(200);

        $uploadedAudioPath = explode('/', $response->getOriginalContent()["response"]["url"]);
        $len = count($uploadedAudioPath);

        $uploadedAudioName = '';
        if ($len > 2) {
            $uploadedAudioName = $uploadedAudioPath[$len - 2] . '/' . $uploadedAudioPath[$len - 1];
        }
        Storage::disk($this->storageDriver)->assertExists($uploadedAudioName);
    }
}
