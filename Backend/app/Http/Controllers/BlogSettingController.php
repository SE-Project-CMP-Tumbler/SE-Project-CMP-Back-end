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
 *              @OA\Property(property="submissions_guidelines", type="string", example="To approve a submitted post it should be free of violence."),),
 *          @OA\Property(property="allow_messages", type="bool", example=true),
 *          @OA\Property(property="queue_settings", type="object",
 *              @OA\Property(property="times_per_day", type="int", example=5),
 *              @OA\Property(property="start_hour", type="int", example=12),
 *              @OA\Property(property="end_hour", type="int", example=14),),))),
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
/**
 * @OA\Put(
 * path="/blog_settings/{blog_id}",
 * summary="Updates the settings of a specific blog",
 * description="Updates the settings options of a specific blog",
 * operationId="updateBlogSettings",
 * tags={"Blogs"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *  name="blog_id",
 *  description="The id of the blog its settings would be updated",
 *  required=true,
 *  in="path",
 *  @OA\Schema(
 *      type="integer")),
 * @OA\RequestBody(
 *  required=true,
 *  description="The Request Body can have one or more key:value pairs, i.e the route can update a single settings option or many settings options via 1 request.
 *  The Request Body need to contain any of the following key value pairs, i.e the request body doesn't need to contain all the following example's key: value pairs.
 *  A request body containing 1 key: value pair will update its corresponding settings' option only.
 *  The key: value pairs specified in the request body are the pairs whose corresponding settings option will be updated only.",
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
 *              @OA\Property(property="submissions_guidelines", type="string", example="To approve a submitted post it should be free of violence."),),
 *          @OA\Property(property="allow_messages", type="bool", example=true),
 *          @OA\Property(property="queue_settings", type="object",
 *              @OA\Property(property="times_per_day", type="int", example=5),
 *              @OA\Property(property="start_hour", type="int", example=12),
 *              @OA\Property(property="end_hour", type="int", example=14),),))),
 * 
 * @OA\Response(
 *  response=401,
 *  description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized to update this blog settings"}),)), 
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