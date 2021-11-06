<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowTagController extends Controller
{
/**
 * @OA\Post(
 * path="/follow_tag/{tag_description}",
 * summary="Follows a specific tag",
 * description="Add a new follow relation between the blog and the tag",
 * operationId="followTag",
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
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified id was not found"}))),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */
/**
 * @OA\Delete(
 * path="/follow_tag/{tag_description}",
 * summary="Unfollows a specific tag",
 * description="Remove the follow relation between the blog and the tag",
 * operationId="unfollowTag",
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
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}))),
 * 
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"A Tag with the specified id was not found"}))),
 * 
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * 
 *  @OA\Response(
 *    response=500,
 *    description="Internal server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"error"})))
 * )
 */
/**
 * @OA\Get(
 * path="/follow_tag",
 * summary="Get all tags the blog follows",
 * description="Returns list of all tags the blog follow",
 * operationId="getfollowingTags",
 * tags={"Tags"},
 * security={ {"bearer": {} }},
 *  @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *       @OA\Property(property="response",type="object",
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example="")))))),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not found"}))),
 *  
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"}))),
 * )
 */

}