<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GifController extends Controller
{
/**
 * @OA\Get(
 * path="/gif",
 * summary="Get all gifs",
 * description="Get all gifs to render them in the ui",
 * operationId="getAllGifs",
 * tags={"Gif"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *          @OA\Property(property="gif_url", type="url", example="/storage/gif_url.gif"),
 *       ),
 *   ),
 * ),
 * @OA\Response(
 *   response=403,
 *   description="Forbidden",
 *   @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}))
 * ),
 * @OA\Response(
 *   response=401,
 *   description="Unauthorized",
 *   @OA\JsonContent(
 *    @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
 *    ),
 * ),
 *)
 */
}
