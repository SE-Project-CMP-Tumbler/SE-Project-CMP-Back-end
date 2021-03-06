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
use App\Services\UploadFileService;
use Illuminate\Http\Request;

/**
 *  UploadFileController deals with uploading photos, videos and audios
 * @method uploadPhoto(Request $request)
 * @method uploadExtImage(Request $request)
 * @method uploadAudio(Request $request)
 * @method uploadVideo(Request $request)
 * @method uploadExtVideo(Request $request)
 */
class UploadFileController extends Controller
{
   /**
    * @OA\Post(
    * path="/upload_photo",
    * summary="upload a photo",
    * description="upload the image in the request body and get the url for that image back in the response",
    * operationId="uploadPhoto",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
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
     **/
    public function uploadImage(ImageRequest $request)
    {
        $request->validated();
        $finalImage = (new UploadFileService())->validateImageService($request->file('image'));
        if ($finalImage) {
            return $this->generalResponse(new ImageResource($finalImage), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_ext_photo",
    * summary="upload an external photo",
    * description="upload the image from imageUrl
    * in the request body and get the url for that image back in the response",
    * operationId="uploadExtPhoto",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
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
     **/
    public function uploadExtImage(ExtImageRequest $request)
    {
        $request->validated();
        $finalImage = (new UploadFileService())->validateExtImageService($request->imageUrl);
        if ($finalImage[0]) {
            return $this->generalResponse(new ImageResource($finalImage[1]), "ok", "200");
        } else {
            return $this->errorResponse($finalImage[1], 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_base64_photo",
    * summary="upload a photo in base64 format",
    * description="upload the image in the request body and get the url for that image back in the response",
    * operationId="uploadBase64Photo",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description= "b64_image: is the image data encoded in base64 form according to RFC 2397" ,
    *    @OA\JsonContent(
    *       @OA\Property(property="b64_image", type="string", example="data:image/png;base64,this-is-the-base64-encode-string"),
    * )),
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
     * upload a photo encoded in base64
     *
     * get the photo from {b64_image} key in the request body.
     * upload a photo of type jpg,jpeg,png,bmp,gif with maximum size of 100M
     * and store that photo in public/images/{name.ext},
     * the {name} will consist of 40 random chars [a-z0-9],
     * and the {ext} will be the uploaded image extension
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     **/
    public function uploadBase64Image(Request $request)
    {
        $rules = ['b64_image' => 'required'];
        $request->validate($rules);
        $finalImage = (new UploadFileService())->validateBase64ImageService($request->b64_image);
        if ($finalImage) {
            return $this->generalResponse(new ImageResource($finalImage), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_audio",
    * summary="upload a audio",
    * description="upload the audio in the request body and get the url for that audio back in the response",
    * operationId="uploadAudio",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
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
     **/
    public function uploadAudio(AudioRequest $request)
    {
        $request->validated();
        $finalAudio = (new UploadFileService())->validateAudioService($request->file('audio'));
        if ($finalAudio) {
            return $this->generalResponse(new AudioResource($finalAudio), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_base64_audio",
    * summary="upload a audio in base64 format",
    * description="upload the image in the request body and get the url for that image back in the response",
    * operationId="uploadBase64Audio",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description= "b64_audio: is the image data encoded in base64 form according to RFC 2397" ,
    *    @OA\JsonContent(
    *       @OA\Property(property="b64_audio", type="string", example="data:audio/mp3;base64,this-is-the-base64-encode-string"),
    * )),
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
     * upload a audio encoded in base64
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     **/
    public function uploadBase64Audio(Request $request)
    {
        $rules = ['b64_audio' => 'required'];
        $request->validate($rules);
        $finalAudio = (new UploadFileService())->validateBase64AudioService($request->b64_audio);
        if ($finalAudio) {
            return $this->generalResponse(new AudioResource($finalAudio), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_video",
    * summary="upload a video",
    * description="upload the video in the request body and get the url for that video back in the response",
    * operationId="uploadVideo",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
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
     **/
    public function uploadVideo(VideoRequest $request)
    {
        $request->validated();
        $finalVideo = (new UploadFileService())->validateVideoService($request->file('video'));
        if ($finalVideo) {
            return $this->generalResponse(new VideoResource($finalVideo), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_ext_video",
    * summary="upload an external video",
    * description="upload the video from videoUrl
    * in the request body and get the url for that vidoe back in the response",
    * operationId="uploadExtVideo",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
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
     **/
    public function uploadExtVideo(ExtVideoRequest $request)
    {
        $request->validated();
        $finalVideo = (new UploadFileService())->validateExtVideoService($request->videoUrl);
        if ($finalVideo[0]) {
            return $this->generalResponse(new VideoResource($finalVideo[1]), "ok", "200");
        } else {
            return $this->errorResponse($finalVideo[1], 422);
        }
    }

   /**
    * @OA\Post(
    * path="/upload_base64_video",
    * summary="upload a video in base64 format",
    * description="upload the image in the request body and get the url for that image back in the response",
    * operationId="uploadBase64Video",
    * tags={"Upload"},
    * security={ {"bearer": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description= "b64_video: is the image data encoded in base64 form according to RFC 2397" ,
    *    @OA\JsonContent(
    *       @OA\Property(property="b64_video", type="string", example="data:video/mp4;base64,this-is-the-base64-encode-string"),
    * )),
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
     * upload a Video encoded in base64
     *
     * @param Reequest $request represents the incoming request body
     * @return json
     **/
    public function uploadBase64Video(Request $request)
    {
        $rules = ['b64_video' => 'required'];
        $request->validate($rules);
        $finalVideo = (new UploadFileService())->validateBase64VideoService($request->b64_video);
        if ($finalVideo) {
            return $this->generalResponse(new VideoResource($finalVideo), "ok", "200");
        } else {
            return $this->errorResponse("Unprocessable Entity", 422);
        }
    }
}
