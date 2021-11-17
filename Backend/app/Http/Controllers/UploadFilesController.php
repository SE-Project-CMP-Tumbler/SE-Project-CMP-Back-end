<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudioRequest;
use App\Http\Requests\ExtImageRequest;
use App\Http\Requests\ExtVideoRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\VideoRequest;
use App\Http\Resources\AudioResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;
use App\Models\Audio;
use App\Models\Image;
use App\Models\Video;
use Embed\Embed;
use FFMpeg\FFMpeg;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use function public_path;

/**
 *  UploadFilesController deals with uploading photos, videos and audios
 * @method uploadPhoto(Request $request)
 */
class UploadFilesController extends Controller
{
   /**
    * @OA\Post(
    * path="/upload_photo/{blog_id}",
    * summary="upload a photo",
    * description="upload the image in the request body and get the url for that image back in the response",
    * operationId="uploadPhoto",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\Parameter(
    *          name="blog_id",
    *          description="Blog_id ",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer")),
    * @OA\RequestBody(
    *    required=true,
    *    @OA\MediaType(
    *       mediaType="multipart/form-data",
    *       @OA\Schema(
    *           @OA\Property(
    *             description="file to upload",
    *             property="image",
    *             type="file",
    *          ),
    *           required={"file"}
    *       )
    *    )
    * ),
    * @OA\Response(
    *    response=200,
    *    description="Successful response",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
    *       @OA\Property(property="response",type="object",
    *         @OA\Property(property="url", type="url", example="/storage/image_url.jpg"),
    *         @OA\Property(property="width", type="integer", example=360),
    *         @OA\Property(property="height", type="integer", example=168),
    *         @OA\Property(property="orignal_filename",type="string",example="home.jpg"),
    *         @OA\Property(property="rotation", type="boolean", example=false),
    *         @OA\Property(property="upload_id", type="boolean", example=false),
    *       ),
    *   ),
    * ),
    * @OA\Response(
    *    response=401,
    *    description="Unauthorized",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
    *        )
    * ),
    * @OA\Response(
    *    response=403,
    *    description="Forbidden",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
    *        )
    * ),
    * @OA\Response(
    *    response=404,
    *    description="Not found",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
    *        )
    * ),
    * @OA\Response(
    *    response=500,
    *    description="Internal Server error",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
    *        )
    *  )
    * )
    **/

    /**
     * upload a photo
     *
     * get the photo from {image} key in the request body.
     * upload a photo of type jpg,jpeg,png,bmp,gif with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     * @throws conditon
     **/
    public function uploadPhoto(ImageRequest $request)
    {
        // useful methods for dealing with files
        // guessExtension(), getMimeType(), store(), asStore(), storePublicly(), move(), getClientOriginalName()
        // getClientMimeType(), guessClientExtension(), getSize(), getError(), isValid()

        // Note: edit upload_max_filesize in /etc/php/8.0/cli/php.ini and /etc/php/8.0/appache2/php.ini
        // $request->validate();
        // $request('image')->store('dir'); // sotres it with random name of -chars in /storage/doc/{dir}/{name.ext}

        $uploadedImage = $request->file('image');
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
        return $this->general_response(new ImageResource($finalImage), "ok", "200");
    }

   /**
    * @OA\Post(
    * path="/upload_ext_photo/{blog_id}",
    * summary="upload an external photo",
    * description="upload the image from imageUrl
    * in the request body and get the url for that image back in the response",
    * operationId="uploadExtPhoto",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\Parameter(
    *          name="blog_id",
    *          description="Blog_id ",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer")),
    * @OA\RequestBody(
    *   required=true,
    *   description="imageUrl: is the url to fetch the image from",
    *   @OA\JsonContent(
    *       @OA\Property(property="imageUrl", type="url", example="http://i.stack.imgur.com/52Ha1.png"),
    *   ),
    * ),
    * @OA\Response(
    *    response=200,
    *    description="Successful response",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
    *       @OA\Property(property="response",type="object",
    *         @OA\Property(property="url", type="url", example="/storage/image_url.jpg"),
    *         @OA\Property(property="width", type="integer", example=360),
    *         @OA\Property(property="height", type="integer", example=168),
    *         @OA\Property(property="orignal_filename",type="string",example="home.jpg"),
    *         @OA\Property(property="rotation", type="boolean", example=false),
    *         @OA\Property(property="upload_id", type="boolean", example=false),
    *       ),
    *   ),
    * ),
    * @OA\Response(
    *    response=401,
    *    description="Unauthorized",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
    *        )
    * ),
    * @OA\Response(
    *    response=403,
    *    description="Forbidden",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
    *        )
    * ),
    * @OA\Response(
    *    response=404,
    *    description="Not found",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
    *        )
    * ),
    * @OA\Response(
    *    response=422,
    *    description="Not Supported",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not supported"})
    *        )
    * ),
    * @OA\Response(
    *    response=500,
    *    description="Internal Server error",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
    *        )
    *  )
    * )
    **/

    /**
     * upload an image form external url
     *
     * get the photo from {imageUrl} key in the request body.
     * upload a photo of type jpg,jpeg,png,bmp,gif with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     * @throws conditon
     **/
    public function uploadExtPhoto(ExtImageRequest $request)
    {
        // $request->validate();
        $headerResponse = get_headers($request->imageUrl, 1);
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
            $uploadedImage = file_get_contents($request->imageUrl);
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
            return $this->general_response(new ImageResource($finalImage), "ok", "200");
        }

        return $this->general_response("", "not supported image type", "422");
    }

   /**
    * @OA\Post(
    * path="/upload_audio/{blog_id}",
    * summary="upload a audio",
    * description="upload the audio in the request body and get the url for that audio back in the response",
    * operationId="uploadAudio",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\Parameter(
    *          name="blog_id",
    *          description="Blog_id ",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer")),
    * @OA\RequestBody(
    *    required=true,
    *    @OA\MediaType(
    *       mediaType="multipart/form-data",
    *       @OA\Schema(
    *           @OA\Property(
    *             description="file to upload",
    *             property="audio",
    *             type="file",
    *          ),
    *           required={"file"}
    *       )
    *    )
    * ),
    * @OA\Response(
    *    response=200,
    *    description="Successful response",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
    *       @OA\Property(property="response",type="object",
    *         @OA\Property(property="url", type="url", example="/storage/audio_url.jpg"),
    *         @OA\Property(property="album_art_url",type="boolean",example=false),
    *       ),
    *   ),
    * ),
    * @OA\Response(
    *    response=401,
    *    description="Unauthorized",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
    *        )
    * ),
    * @OA\Response(
    *    response=403,
    *    description="Forbidden",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
    *        )
    * ),
    * @OA\Response(
    *    response=404,
    *    description="Not found",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
    *        )
    *  ),
    * @OA\Response(
    *    response=500,
    *    description="Internal Server error",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
    *        )
    *  )
    * )
    **/

    /**
     * upload an audio file
     *
     * get the audio from {audio} key in the request body.
     * upload an audio file of type mp3,wav,3gp,3gpp with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     * @throws conditon
     **/
    public function uploadAudio(AudioRequest $request)
    {
        // $request->validate();
        $uploadedAudio = $request->file('audio');
        $newAudioName = Str::random(40) . '.' . $uploadedAudio->getClientOriginalExtension();
        $newAudioUrl = $uploadedAudio->move(public_path('audios'), $newAudioName);
        $finalAudio = new Audio([
            'url' => $newAudioUrl->getRealPath(),
            'album_art_url' => false
        ]);
        return $this->general_response(new AudioResource($finalAudio), "ok", "200");
    }

   /**
    * @OA\Post(
    * path="/upload_video/{blog_id}",
    * summary="upload a video",
    * description="upload the video in the request body and get the url for that video back in the response",
    * operationId="uploadVideo",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\Parameter(
    *          name="blog_id",
    *          description="Blog_id ",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer")),
    * @OA\RequestBody(
    *    required=true,
    *    @OA\MediaType(
    *       mediaType="multipart/form-data",
    *       @OA\Schema(
    *           @OA\Property(
    *             description="file to upload",
    *             property="video",
    *             type="file",
    *          ),
    *           required={"file"}
    *       )
    *    )
    * ),
    * @OA\Response(
    *    response=200,
    *    description="Successful response",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
    *       @OA\Property(property="response",type="object",
    *         @OA\Property(property="url", type="url", example="/storage/video_url.jpg"),
    *         @OA\Property(property="width", type="integer", example=360),
    *         @OA\Property(property="height", type="integer", example=168),
    *         @OA\Property(property="size", type="integer", example=1031273),
    *         @OA\Property(property="duration", type="float", example=13.760000),
    *         @OA\Property(property="audio_codec", type="string", example="acc"),
    *         @OA\Property(property="video_codec", type="string", example="h264"),
    *         @OA\Property(property="preview_image_url",type="url",example="/storage/preview_image_url.jpg"),
    *       ),
    *   ),
    * ),
    * @OA\Response(
    *    response=401,
    *    description="Unauthorized",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
    *        )
    * ),
    * @OA\Response(
    *    response=403,
    *    description="Forbidden",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
    *        )
    * ),
    * @OA\Response(
    *    response=404,
    *    description="Not found",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
    *        )
    *  ),
    * @OA\Response(
    *    response=500,
    *    description="Internal Server error",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
    *        )
    *  )
    * )
    **/

    /**
     * upload an video file
     *
     * get the video from {video} key in the request body.
     * upload a video of type mp4,mkv,mov,flv,avi,webm with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     * @throws conditon
     **/
    public function uploadVideo(VideoRequest $request)
    {
        // $request->validate();
        $uploadedVideo = $request->file('video');
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
        return $this->general_response(new VideoResource($finalVideo), "ok", "200");
    }

   /**
    * @OA\Post(
    * path="/upload_ext_video/{blog_id}",
    * summary="upload an external video",
    * description="upload the video from videoUrl
    * in the request body and get the url for that vidoe back in the response",
    * operationId="uploadExtVideo",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\Parameter(
    *          name="blog_id",
    *          description="Blog_id ",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer")),
    * @OA\RequestBody(
    *   required=true,
    *   description="videoUrl: is the url to fetch the video from",
    *   @OA\JsonContent(
    *       @OA\Property(property="videoUrl", type="url", example="https://www.youtube.com/watch?v=N69iZPwRneM"),
    *   ),
    * ),
    * @OA\Response(
    *    response=200,
    *    description="Successful response",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object",example={"status":"200","msg":"OK"}),
    *       @OA\Property(property="response",type="object",
    *         @OA\Property(property="body", type="string",
    *                      example="<iframe width='200' height='113' src='https://www.youtube.com/embed/QC61CKXgxkU?list=PLS1QulWo1RIa-sDLWbP01sEnlm_Bxmvqs' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"),
    *         @OA\Property(property="width", type="integer", example=360),
    *         @OA\Property(property="height", type="integer", example=168),
    *       ),
    *   ),
    * ),
    * @OA\Response(
    *    response=401,
    *    description="Unauthorized",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
    *        )
    * ),
    * @OA\Response(
    *    response=403,
    *    description="Forbidden",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"forbidden"})
    *        )
    * ),
    * @OA\Response(
    *    response=404,
    *    description="Not found",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
    *        )
    * ),
    * @OA\Response(
    *    response=422,
    *    description="Not Supported",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not supported"})
    *        )
    * ),
    * @OA\Response(
    *    response=500,
    *    description="Internal Server error",
    *    @OA\JsonContent(
    *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
    *        )
    *  )
    * )
    **/

    /**
     * upload an video file
     *
     * get the video from {videoUrl} key in the request body.
     * upload a video of type mp4,mkv,mov,flv,avi,webm with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     * @throws conditon
     **/
    // TODO: add ExtVideoResource ?!
    public function uploadExtVideo(ExtVideoRequest $request)
    {
        // $request->validate();
        $embed = new Embed();
        $info = $embed->get($request->videoUrl);
        if ($info) {
            $successObj = [
                'body' => $info->code->html,
                'width' => $info->code->width,
                'height' => $info->code->height,
            ];
            return $this->general_response($successObj, "ok", "200");
        }
        return $this->general_response("", "not supported", "422");
    }
}
