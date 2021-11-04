<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogSettingController extends Controller
{
/**
 * @OA\Get(
 * path="/blog_settings/{blog_id}",
 * summary="Get the settings of a specific blog",
 * description="Returns list of blog settings
 * Note that: Response may have 1 or 2 attributes changing based on whether it's primary or secondry
 *  Extra Attributes for a primary blog settings:
 *      share_likes: bool to whether allow or not sharing likes on a specific blog's website,
 *      share_followings: bool to whether allow or not sharing followings on a specific blog's website
 *  Extra Attributes for a secondry blog settings:
 *      allow_password: bool to whether or not allow having a password on a specific secondry blog",
 * 
 * operationId="getBlogSettings",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *  name="blog_id",
 *  description="The id of the blog its settings would be retrieved",
 *  required=true,
 *  in="path",
 *  @OA\Schema(
 *      type="integer")),
 * @OA\Response(
 *  response=200,
 *  description="Successful response",
 *  @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="id",type="integer", example=2),
 *          @OA\Property(property="replies_settings", type="string", example="Everyone can reply"),
 *          @OA\Property(property="ask_settings", type="object", 
 *              @OA\Property(property="allow_ask", type="bool", example=false),
 *              @OA\Property(property="ask_page_title", type="string", example=""),
 *              @OA\Property(property="allow_anonymous_questions", type="bool", example=""),),
 *          @OA\Property(property="submissions_settings", type="object", 
 *              @OA\Property(property="allow_submittions", type="bool", example=true),
 *              @OA\Property(property="submissions_page_title", type="string", example="Submit a post"),
 *              @OA\Property(property="submissions_guidelines", type="string", example="To approve a submitted post it should be free of violence."),
 *              @OA\Property(property="allowed_post_types", type="object", 
 *                  @OA\Property(property="text", type="bool", example=true),
 *                  @OA\Property(property="photo", type="bool", example=false),
 *                  @OA\Property(property="quote", type="bool", example=true),
 *                  @OA\Property(property="link", type="bool", example=false),
 *                  @OA\Property(property="video", type="bool", example=false),),),
 *          @OA\Property(property="allow_messages", type="bool", example=true),
 *          @OA\Property(property="queue_settings", type="object",
 *              @OA\Property(property="times_per_day", type="int", example=5),
 *              @OA\Property(property="start_hour", type="int", example=12),
 *              @OA\Property(property="end_hour", type="int", example=14),),
 *          @OA\Property(property="language_used_on_blog", type="string", example="English"),
 *          @OA\Property(property="timezone", type="string", example="(GMT -4:00) Eastern Time (US & Canada)"),
 *          @OA\Property(property="visibility_settings", type="object",
 *              @OA\Property(property="visibility_of_site", type="bool", example=false),
 *              @OA\Property(property="visibility_from_search", type="bool", example=true),),))),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized to get this blog settings"}),)), 
 * 
 * @OA\Response(
 *  response=404,
 *  description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"The blog id specified was not found"}),)), 
 * 
 * @OA\Response(
 *  response=500,
 *  description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal server error"}),)), 
 * 
 * 
 * )
 */

}