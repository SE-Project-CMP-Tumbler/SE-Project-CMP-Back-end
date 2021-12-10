<?php

namespace App\Services;

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
        $newImage = $uploadedImage->store('', 'images');
        $newImagePath = Storage::disk('images')->getAdapter()->getPathPrefix() . $newImage;
        [$width, $height] = getimagesize($newImagePath);
        $finalImage = new Image([
            'url' => Storage::disk('images')->url($newImage),
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
            $newImageName = Str::random(40) . '.' . $imageExt;
            $newImagePath = Storage::disk('images')->getAdapter()->getPathPrefix() . $newImageName;
            file_put_contents($newImagePath, $uploadedImage);
            [$width, $height] = getimagesize($newImagePath);
            $finalImage = new Image([
                'url' => Storage::disk('images')->url($newImageName),
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
        $newAudio = $uploadedAudio->store('', 'audios');
        $newAudioPath = Storage::disk('audios')->getAdapter()->getPathPrefix() . $newAudio;
        $finalAudio = new Audio([
            'url' => Storage::disk('audios')->url($newAudio),
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
        $newVideo = $uploadedVideo->store('', 'videos');
        $newVideoPath = Storage::disk('videos')->getAdapter()->getPathPrefix() . $newVideo;

        $ffprobe = FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);

        // $ffprobe = FFMpeg::create();
        $video = $ffprobe->open($newVideoPath);
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
            'url' => Storage::disk('videos')->url($newVideo),
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
}
