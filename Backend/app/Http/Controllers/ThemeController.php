<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
 /**
 * @OA\Put(
 * path="/blog/{blog_id}/theme",
 * summary="change blog theme",
 * description="change/update blog theme such as avater, background color, header image, ...",
 * operationId="updateTheme",
 * tags={"Blog Theme"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *   name="blog_id",
 *   description="blog id",
 *   required=true,
 *   in="path",
 *    @OA\Schema(
 *       type="intger",
 *    )
 * ),
 * @OA\RequestBody(
 *   required=true,
 *    @OA\JsonContent(
 *       @OA\Property(property="title", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="CPP Programming"),
 *           @OA\Property(property="color", type="string", example="#000000"),
 *           @OA\Property(property="font", type="string", example="Gibson"),
 *           @OA\Property(property="font_weight", type="string", example="bold"),
 *         )
 *       ),
 *       @OA\Property(property="description", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="Just for cpp nurds"),
 *         )
 *       ),
 *       @OA\Property(property="background_color", type="string", example="#FFFFFF"),
 *       @OA\Property(property="accent_color", type="string", example="#e17e66"),
 *       @OA\Property(property="body_font", type="string", example="Helvetica Neue"),
 *       @OA\Property(property="header_image", type="array",
 *         @OA\Items(
 *            @OA\Property(property="url", type="string",format="byte", example="assksineuug"),
 *         )
 *       ),
 *       @OA\Property(property="avatar", type="array",
 *           @OA\Items(
 *              @OA\Property(property="url", type="string",format="byte", example="aksmdnurjrj"),
 *              @OA\Property(property="shape", type="string", example="circle"),
 *           )
 *       ),
 *    )
 *  ),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *      @OA\Property(property="response", type="object",
 *       @OA\Property(property="theme-id", type="int", example="123456789"),
 *       @OA\Property(property="title", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="CPP Programming"),
 *           @OA\Property(property="color", type="string", example="#000000"),
 *           @OA\Property(property="font", type="string", example="Gibson"),
 *           @OA\Property(property="font_weight", type="string", example="bold"),
 *         )
 *       ),
 *       @OA\Property(property="description", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="Just for cpp nurds"),
 *         )
 *       ),
 *       @OA\Property(property="background_color", type="string", example="#FFFFFF"),
 *       @OA\Property(property="accent_color", type="string", example="#e17e66"),
 *       @OA\Property(property="body_font", type="string", example="Helvetica Neue"),
 *       @OA\Property(property="header_image", type="array",
 *         @OA\Items(
 *            @OA\Property(property="url", type="url", example="/storage/example_image.jpg"),
 *         )
 *       ),
 *       @OA\Property(property="avatar", type="array",
 *           @OA\Items(
 *              @OA\Property(property="url", type="url", example="/storage/example_image_avatar.jpg"),
 *              @OA\Property(property="shape", type="string", example="circle"),
 *           )
 *        )
 *     )
 *    )
 *  ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Unauthorized"})
 *        )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"forbidden"})
 *        )
 *  ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
 *        )
 *     ),
 * )
 */


 /**
 * @OA\Get(
 * path="/blog/{blog_id}/theme",
 * summary="get current blog them options",
 * description="get current blog theme options such as avater, background color, header image, ...",
 * operationId="getTheme",
 * tags={"Blog Theme"},
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *   name="blog_id",
 *   description="blog id",
 *   required=true,
 *   in="path",
 *    @OA\Schema(
 *       type="intger",
 *    )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *      @OA\Property(property="response", type="object",
 *       @OA\Property(property="theme-id", type="int", example="123456789"),
 *       @OA\Property(property="title", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="CPP Programming"),
 *           @OA\Property(property="color", type="string", example="#000000"),
 *           @OA\Property(property="font", type="string", example="Gibson"),
 *           @OA\Property(property="font_weight", type="string", example="bold"),
*          )
 *       ),
 *       @OA\Property(property="description", type="array",
 *         @OA\Items(
 *           @OA\Property(property="text", type="string", example="Just for cpp nurds"),
 *         )
 *       ),
 *       @OA\Property(property="background_color", type="string", example="#FFFFFF"),
 *       @OA\Property(property="accent_color", type="string", example="#e17e66"),
 *       @OA\Property(property="body_font", type="string", example="Helvetica Neue"),
 *       @OA\Property(property="header_image", type="array",
 *         @OA\Items(
 *            @OA\Property(property="url", type="url", example="/storage/example_image.jpg"),
 *         )
 *       ),
 *       @OA\Property(property="avatar", type="array",
 *           @OA\Items(
 *              @OA\Property(property="url", type="url", example="/storage/example_image_avatar.jpg"),
 *              @OA\Property(property="shape", type="string", example="circle"),
 *           )
 *        )
 *      )
 *    )
 *   ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Unauthorized"})
 *        )
 *  ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found question"})
 *        )
 *     ),
 * )
 */
}
