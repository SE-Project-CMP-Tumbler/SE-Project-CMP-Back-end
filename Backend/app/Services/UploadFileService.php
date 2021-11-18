<?php

namespace App\Services;

use App\Models\Audio;
use App\Models\Image;
use App\Models\Video;
use Embed\Embed;
use FFMpeg\FFMpeg;
use Illuminate\Support\Str;

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
        $newImageName = Str::random(40) . '.' . $uploadedImage->getClientOriginalExtension();
        $newImage = $uploadedImage->move(public_path('images'), $newImageName);
        [$width, $height] = getimagesize($newImage);
        $finalImage = new Image([
            'url' => $newImage->getRealPath(),
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
     * @return \App\Models\Image|null
     **/
    public function validateExtImageService(string $imageUrl)
    {
        if (is_null($imageUrl)) {
            return null;
        }
        $headerResponse = get_headers($imageUrl, 1);
        $validTypes = ["jpg", "jpeg", "png", "gif", "bmp"];
        $isValidType = false;
        $imageExt = "";
        if (strpos($headerResponse[0], "200" == true)) {
            foreach ($validTypes as $item) {
                if ($headerResponse['Content-Type'] == "image/" . $item) {
                    $isValidType = true;
                    $imageExt = $item;
                    break;
                }
            }
        }
        // TODO: add the same size resteriction, get the orignal_filename
        if ($isValidType) {
            // $filePath = pathinfo($request->imageUrl);
            $uploadedImage = file_get_contents($imageUrl);
            $newImageName = Str::random(40) . '.' . $imageExt;
            $newImagePath = public_path('images/' . $newImageName);
            file_put_contents($newImagePath, $uploadedImage);
            [$width, $height] = getimagesize($newImagePath);
            $finalImage = new Image([
                'url' => $newImagePath,
                'width' => $width,
                'height' => $height,
                'orignal_filename' => '',
                'routation' => false,
                'upload_id' => false
            ]);
            return $finalImage;
        }
        return null;
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
        $newAudioName = Str::random(40) . '.' . $uploadedAudio->getClientOriginalExtension();
        $newAudioUrl = $uploadedAudio->move(public_path('audios'), $newAudioName);
        $finalAudio = new Audio([
            'url' => $newAudioUrl->getRealPath(),
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
        $newVideoName = Str::random(40) . '.' . $uploadedVideo->getClientOriginalExtension();
        $newVideoObj = $uploadedVideo->move(public_path('videos'), $newVideoName);
        $newVideoUrl = $newVideoObj->getRealPath();

        // $ffprobe = FFMpeg::create([
        //     'ffmpeg.binaries' => exec('which ffmpeg'),
        //     'ffprobe.binaries' => exec('which ffprobe'),
        // ]);

        $ffprobe = FFMpeg::create();
        $video = $ffprobe->open($newVideoUrl);
        $video_dimensions = $video
        ->getStreams()                  // extracts streams informations
        ->videos()                      // filters video streams
        ->first()                       // returns the first video stream
        ->getDimensions();              // returns a FFMpeg\Coordinate\Dimension object

        $size = $newVideoObj->getSize();
        $width = $video_dimensions->getWidth();
        $height = $video_dimensions->getHeight();
        $duration = $video->getFormat()->get('duration');
        $video_codec = $video->getStreams()->videos()->first()->get('codec_name');
        $audio_codec = $video->getStreams()->audios()->first()->get('codec_name');

        $finalVideo = new Video([
            'url' => $newVideoUrl,
            'width' => $width,
            'height' => $height,
            'size' => $size,
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
     * validate the type and size of an uploaded video
     * then allow or reject that video.
     *
     * @param string $videoUrl the video link
     * @return \App\Models\Video|null
     **/
    public function validateExtVideoService(string $videoUrl)
    {
        if (is_null($videoUrl)) {
            return null;
        }
        $embed = new Embed();
        $info = $embed->get($videoUrl);
        if ($info) {
            $finalVideo = new Video([
                'body' => $info->code->html,
                'width' => $info->code->width,
                'height' => $info->code->height,
            ]);
            return $finalVideo;
        }
        return null;
    }
}
