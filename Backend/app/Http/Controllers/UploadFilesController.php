<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
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
 */

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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
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
 */

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
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
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
 */
}
