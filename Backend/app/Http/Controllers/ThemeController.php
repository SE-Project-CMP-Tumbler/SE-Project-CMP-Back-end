<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Misc\Helpers\Config;
use App\Http\Misc\Helpers\Success;
use App\Models\Blog;
use App\Models\Theme;
use App\Models\Like;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ThemeResource;
use App\Http\Requests\ThemeRequest;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\PostCollection;
use App\Http\Requests\BlogRequest;
use App\Services\BlogService;

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
     * update specific theme of blog
     * @param integer $blogI
     * @return \json
     */
    public function update(ThemeRequest $request, $blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        $this->authorize('update', $blog);
        $theme = $blog->theme;
        DB::beginTransaction();
        try {
            $theme->update([
            'color_title' => $request->title[0]['color'] ?? $theme->color_title,
            'font_title' => $request->title[0]['font'] ?? $theme->font_title,
            'font_weight_title' => $request->title[0]['font_weight'] ?? $theme->font_weight_title,
            'background_color' => $request->background_color ?? $theme->background_color,
            'accent_color' => $request->accent_color ?? $theme->accent_color,
            'body_font' => $request->body_font ?? $theme->body_font
            ]);
            $blog->update([
            'description' => $request->description[0]['text'] ?? $blog->description,
            'title' => $request->title[0]['text'] ?? $blog->title,
            'header_image' => $request->header_image[0]['url'] ?? $blog->header_image,
            'avatar' => $request->avatar[0]['url'] ?? $blog->avatar
            ]);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return $this->generalResponse("", "Internal Server Erorr", "500");
        }
        return $this->generalResponse(new ThemeResource($theme), "ok");
    }
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
  /**
     * Get specific theme of blog
     * @param integer $blogI
     * @return \json
     */
    public function show($blogId)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        return $this->generalResponse(new ThemeResource($blog->theme), "ok");
    }
}
