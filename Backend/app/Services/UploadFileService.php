<?php

namespace App\Services;

use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\Config;
use App\Models\Audio;
use App\Models\Image;
use App\Models\Video;
use Embed\Embed;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * UploadFileService handles the logic of uploading files
 * @method validateImageService($uploadedImage)
 * @method validateExtImageService(string $imageUrl)
 * @method validateAudioService($uploadedAudio)
 * @method validateVideoService($uploadedVideo)
 * @method validateExtVideoService(string $videoUrl)
 */
class UploadFileService
{

    /**
     * upload image service
     *
     * validate the type and size of an uploaded image
     * then allow or reject that image.
     *
     * @param \Illuminate\Http\File $uploadedImage the user uploaded image
     * @return \App\Models\Image|null
     **/
    public function validateImageService($uploadedImage)
    {
        if (is_null($uploadedImage)) {
            return null;
        } elseif (is_array($uploadedImage)) {
            $uploadedImage = $uploadedImage[0];
        }
        $dirName = Str::random(40);
        $newImage = $uploadedImage->store($dirName, 'ftp');
        [$width, $height] = getimagesize($uploadedImage);
        $finalImage = new Image([
            'url' => Storage::disk('ftp')->url($newImage),
            'width' => $width,
            'height' => $height,
            'orignal_filename' => $uploadedImage->getClientOriginalName(),
            'rotation' => false,
            'upload_id' => false
        ]);
        return $finalImage;
    }

    /**
     * upload image from url service
     *
     * validate the type and size of an uploaded image
     * then allow or reject that image.
     *
     * @param string $imageUrl the image link
     * @return array
     **/
    public function validateExtImageService(string $imageUrl)
    {
        if (is_null($imageUrl)) {
            return [false, "The imageUrl field is required"];
        }
        try {
            // $crawler = (new Embed())->getCrawler();
            // $uri = $crawler->createUri($imageUrl);
            // $req = $crawler->createRequest("HEAD", $uri);
            // $headerResponse = $crawler->sendRequest($req);
            $headerResponse = get_headers($imageUrl, 1);
        } catch (Throwable $e) {
            return [false, "image url should be valid image url"];
        }
        if ($headerResponse == false) {
            return [false, "image url should be valid image url"];
        }
        $validTypes = Config::VALID_IMAGE_TYPES;
        $isValidType = false;
        $imageExt = "";
        $imageSize = -1;
        // if ($headerResponse->getStatusCode() == 200) {
        if (strpos($headerResponse[0], "200") == true) {
            foreach ($validTypes as $item) {
                // if ($headerResponse->getHeader('Content-Type')[0] == "image/" . $item) {
                if ($headerResponse['Content-Type'] == "image/" . $item) {
                    $isValidType = true;
                    $imageExt = $item;
                    // $imageSize = $headerResponse->getHeader('Content-Length')[0];
                    $imageSize = $headerResponse["Content-Length"];
                    break;
                }
            }
            if ($isValidType == false) {
                return [false, "The image type isn't supported"];
            }
            if ($imageSize == -1 || $imageSize > Config::FILE_UPLOAD_MAX_SIZE) {
                return [false, "Allowed image max size is "  . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"];
            }
            # TODO: get the orignal_filename
            $uploadedImage = file_get_contents($imageUrl);
            $dirName = Str::random(40);
            $newImageName = Str::random(40) . '.' . $imageExt;
            Storage::disk('ftp')->put($dirName . '/' . $newImageName, $uploadedImage);
            [$width, $height] = getimagesizefromstring($uploadedImage);
            $finalImage = new Image([
                'url' => env('APP_EXT_URL') . '/' . $dirName . '/' . $newImageName,
                'width' => $width,
                'height' => $height,
                'orignal_filename' => '',
                'routation' => false,
                'upload_id' => false
            ]);
            return [true, $finalImage];
        }
        return [false, "image url should be valid image url"];
    }

    /**
     * upload image from base64 service
     *
     * validate the type and size of an uploaded image
     * then allow or reject that image.
     *
     * @param \Illuminate\Http\File $uploadedImage the user uploaded image
     * @return \App\Models\Image|null
     **/
    public function validateBase64ImageService($b64Data)
    {
        if (is_null($b64Data)) {
            return null;
        }
        $uploadedImage = Base64Handler::base64Validation(
            $b64Data,
            Config::VALID_IMAGE_TYPES,
            Config::FILE_UPLOAD_MAX_SIZE
        );
        if ($uploadedImage == false) {
            return null;
        }
        $info = getimagesize($uploadedImage);
        $dirName = Str::random(40);
        $newImageName = Str::random(40) . '.' . explode("/", $info["mime"])[1];
        Storage::disk('ftp')->put($dirName . '/' . $newImageName, file_get_contents($uploadedImage));
        $finalImage = new Image([
            'url' => env('APP_EXT_URL') . '/' . $dirName . '/' . $newImageName,
            'width' => $info[0],
            'height' => $info[1],
            'orignal_filename' => '',
            'rotation' => false,
            'upload_id' => false
        ]);
        return $finalImage;
    }

    /**
     * validate the uploaded audio
     *
     * validate the type and size of an uploaded audio
     * then allow or reject that audio.
     *
     * @param \Illuminate\Http\File $uploadedAudio the user uploaded audio
     * @return \App\Models\Audio|null
     **/
    public function validateAudioService($uploadedAudio)
    {
        if (is_null($uploadedAudio)) {
            return false;
        } elseif (is_array($uploadedAudio)) {
            $uploadedAudio = $uploadedAudio[0];
        }
        $dirName = Str::random(40);
        $newAudio = $uploadedAudio->store($dirName, 'ftp');
        $finalAudio = new Audio([
            'url' => Storage::disk('ftp')->url($newAudio),
            'album_art_url' => false
        ]);
        return $finalAudio;
    }

    /**
     * upload audio from base64 service
     *
     * validate the type and size of an uploaded audio
     * then allow or reject that audio.
     *
     * @param \Illuminate\Http\File $uploadedAudio the user uploaded audio
     * @return \App\Models\Audio|null
     **/
    public function validateBase64AudioService($b64Data)
    {
        if (is_null($b64Data)) {
            return null;
        }
        $uploadedAudio = Base64Handler::base64Validation(
            $b64Data,
            Config::VALID_AUDIO_TYPES,
            Config::FILE_UPLOAD_MAX_SIZE
        );
        if ($uploadedAudio == false) {
            return null;
        }
        $info = mime_content_type($b64Data);
        $dirName = Str::random(40);
        $newAudioName = Str::random(40) . '.' . explode("/", $info)[1];
        Storage::disk('ftp')->put($dirName . '/' . $newAudioName, file_get_contents($uploadedAudio));
        $finalAudio = new Audio([
            'url' => env('APP_EXT_URL') . '/' . $dirName . '/' . $newAudioName,
            'album_art_url' => false
        ]);
        return $finalAudio;
    }

    /**
     * validate the uploaded video
     *
     * validate the type and size of an uploaded video
     * then allow or reject that video.
     *
     * @param \Illuminate\Http\File $uploadedVideo the user uploaded video
     * @return \App\Models\Video|null
     **/
    public function validateVideoService($uploadedVideo)
    {
        if (is_null($uploadedVideo)) {
            return false;
        } elseif (is_array($uploadedVideo)) {
            $uploadedVideo = $uploadedVideo[0];
        }
        $dirName = Str::random(40);
        $newVideo = $uploadedVideo->store($dirName, 'ftp');
        $newVideoPath = Storage::disk('ftp')->getAdapter()->getPathPrefix() . $newVideo;

        $ffprobe = FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);

        // $ffprobe = FFMpeg::create();
        $video = $ffprobe->open($uploadedVideo);
        $video_dimensions = $video
        ->getStreams()                  // extracts streams informations
        ->videos()                      // filters video streams
        ->first()                       // returns the first video stream
        ->getDimensions();              // returns a FFMpeg\Coordinate\Dimension object

        // $size = $newVideoObj->getSize();
        $width = $video_dimensions->getWidth();
        $height = $video_dimensions->getHeight();
        $duration = $video->getFormat()->get('duration');

        $vtmp = $video->getStreams()->videos()->first();
        $atmp = $video->getStreams()->audios()->first();
        $video_codec = null;
        $audio_codec = null;

        if ($vtmp) {
            $video_codec = $vtmp->get('codec_name');
        }
        if ($atmp) {
            $audio_codec = $atmp->get('codec_name');
        }

        $finalVideo = new Video([
            'url' => Storage::disk('ftp')->url($newVideo),
            'width' => $width,
            'height' => $height,
            // 'size' => $size,
            'duration' => $duration,
            'audio_codec' => $audio_codec,
            'video_codec' => $video_codec,
            'preview_image_url' => ''
        ]);
        return $finalVideo;
    }

    /**
     * upload video from url service
     *
     * @param string $videoUrl the video link
     * @return array
     **/
    public function validateExtVideoService(string $videoUrl)
    {
        if (is_null($videoUrl)) {
            return [false, "The videoUrl field is required"];
        } elseif (is_array($videoUrl)) {
            $videoUrl = $videoUrl[0];
        }
        $embed = new Embed();
        try {
            $info = $embed->get($videoUrl);
        } catch (Throwable $e) {
            return [false, "video url should be valid video url"];
        }
        if ($info->code) {
            $finalVideo = new Video([
                'body' => $info->code->html,
                'width' => $info->code->width,
                'height' => $info->code->height,
            ]);
            return [true, $finalVideo];
        }
        return [false, "video url should be valid video url"];
    }

    /**
     * upload video from base64 service
     *
     * validate the type and size of an uploaded video
     * then allow or reject that Video.
     *
     * @param \Illuminate\Http\File $uploadedVideo the user uploaded vieo
     * @return \App\Models\Video|null
     **/
    public function validateBase64VideoService($b64Data)
    {
        if (is_null($b64Data)) {
            return null;
        }
        $uploadedVideo = Base64Handler::base64Validation(
            $b64Data,
            Config::VALID_VIDEO_TYPES,
            Config::FILE_UPLOAD_MAX_SIZE
        );
        if ($uploadedVideo == false) {
            return null;
        }
        $info = mime_content_type($b64Data);
        $dirName = Str::random(40);
        $newVideoName = Str::random(40) . '.' . explode("/", $info)[1];
        Storage::disk('ftp')->put($dirName . '/' . $newVideoName, file_get_contents($uploadedVideo));

        $ffprobe = FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);

        // $ffprobe = FFMpeg::create();
        $video = $ffprobe->open($uploadedVideo);
        $video_dimensions = $video
        ->getStreams()                  // extracts streams informations
        ->videos()                      // filters video streams
        ->first()                       // returns the first video stream
        ->getDimensions();              // returns a FFMpeg\Coordinate\Dimension object

        // $size = $newVideoObj->getSize();
        $width = $video_dimensions->getWidth();
        $height = $video_dimensions->getHeight();
        $duration = $video->getFormat()->get('duration');

        $vtmp = $video->getStreams()->videos()->first();
        $atmp = $video->getStreams()->audios()->first();
        $video_codec = null;
        $audio_codec = null;

        if ($vtmp) {
            $video_codec = $vtmp->get('codec_name');
        }
        if ($atmp) {
            $audio_codec = $atmp->get('codec_name');
        }

        $finalVideo = new Video([
            'url' => env('APP_EXT_URL') . '/' . $dirName . '/' . $newVideoName,
            'width' => $width,
            'height' => $height,
            // 'size' => $size,
            'duration' => $duration,
            'audio_codec' => $audio_codec,
            'video_codec' => $video_codec,
            'preview_image_url' => ''
        ]);
        return $finalVideo;
    }
}
