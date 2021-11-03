<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
/**
 * @OA\Post(
 * path="/tag/data/{tag_description}",
 * summary="Create a new tag",
 * description="Creates a specific tag",
 * operationId="createTag",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * 
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),),),
 * 
 *  @OA\Response(
 *    response=409,
 *    description="Conflict",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "409", "msg":"The request could not be completed due to a conflict with the current state of the resource."})))
 * )
 */
/**
 * @OA\Get(
 * path="/tag/data/{tag_description}",
 * summary="Get data of a specific tag",
 * description="Returns data of a specific tag",
 * operationId="getTagData",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * 
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="tag_description",type="string",example="books"),
 *          @OA\Property(property="tag_image",type="string", format="byte", example=""),
 *          @OA\Property(property="followed",type="bool", example=false),
 *          @OA\Property(property="followers_number",type="integer", example=1026),)),),
 * 
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified description was not found"})))
 * )
 */    
/**
 * @OA\Get(
 * path="/tag/posts/{tag_description}?sort=sort_type",
 * summary="Get all posts associated with tag",
 * description="Returns list of all posts associated to a specific tag",
 * operationId="getTagPosts",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 * @OA\Parameter(
 *          name="tag_description",
 *          description="Tag Description",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * 
 * @OA\Parameter(
 *          name="sort",
 *          description="The sort method to retrieve the posts",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string")),
 * 
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *     @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *          @OA\Property(property="tag_description",type="string",example="books"),
 *          @OA\Property(property="posts",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="post_status", type="string", example="published"),
 *                  @OA\Property(property="title", type="string", example="New post"),
 *                  @OA\Property(property="description", type="string", example="new post"),
 *                  @OA\Property(property="chat_title", type="string", example="New post"),
 *                  @OA\Property(property="chat_body", type="string", example="My post"),
 *                  @OA\Property(property="quote_text", type="string", example="New post"),
 *                  @OA\Property(property="quote_resouce", type="string", example="My post"),
 *                  @OA\Property(property="keep_reading", type="integer", example=1),
 *                  @OA\Property(property="images ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", format="byte",example="/storage/imgname2.extension"),
 *                          @OA\Property(property="1", type="string", format="byte", example="/storage/imgname2.extension"),
 *                          @OA\Property(property="2", type="string", format="byte", example="/storage/imgname2.extension"),)),
 *                  @OA\Property(property="video ", type="string", format="byte", example=""),
 *                  @OA\Property(property="audio ", type="string", format="byte", example=""),
 *                  @OA\Property(property="post_type ", type="string", example="text"),
 *                  @OA\Property(property="url_videos ", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="facebook.com"),
 *                          @OA\Property(property="1", type="string", example="google.com"),
 *                          @OA\Property(property="2", type="string", example="yahoo.com"),),),
 *                  @OA\Property(property="post_tags", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="0", type="string", example="books"),
 *                          @OA\Property(property="1", type="string", example="reading"),
 *                          @OA\Property(property="2", type="string", example="stay positive"),)),),),)),),
 * 
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Tag description or sort type was not found"})))
 * )
 */
}