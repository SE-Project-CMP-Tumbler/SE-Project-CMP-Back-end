<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
/**
 * @OA\get(
 * path="/graph/notes/{period}/{rate}",
 * summary="get the notes",
 * description="get the notes for the activity graph",
 * tags={"Activity"},
 * operationId="getNotesActivityGraph",
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="period",
 *          description="the time period that you want to retrieve the data for.
 *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="rate",
 *          description="the time rate that you want to retrieve the data with.
 *  ( 0 -> hourly) , (1 -> daily),
 *  note: if the period=1, then the rate must equal 0.",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="notes_count", type="integer", example=16),
 *       @OA\Property(property="new_followers_count", type="integer", example=6),
 *       @OA\Property(property="total_followers_count", type="integer", example=326),
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="notes", type="integer", example=5),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="notes", type="integer", example=7),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="notes", type="integer", example=2),
 *              @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *          )),
 *       @OA\Property(property="top_post", type="object", 
 *              @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *              @OA\Property(property="total_notes_count", type="integer", example=14),
 *              @OA\Property(property="notes_count", type="integer", example=2),
 *              ),
 *       @OA\Property(property="biggest_fan", type="array",
 *          @OA\Items(
 * 
 *               @OA\Property(property="name", type="string", example="mycppblog"),
 *               @OA\Property(property="blog_id", type="int", example="123456789"),
 *               @OA\Property(property="blog_username", type="int", example="123456789"),
 *              @OA\Property(property="blog_avatar", type="string", example="storage/blogs/avatar2125"),    
 *              @OA\Property(property="blog_avatar_shape", type="string", example="square"), 
 *               @OA\Property(property="followed", type="bool", example=true),
 *               @OA\Property(property="theme_id", type="int", example="123456789"),
 *               @OA\Property(property="title", type="array",
 *                   @OA\Items(
 *                       @OA\Property(property="text", type="string", example="CPP Programming"),
 *                       @OA\Property(property="show", type="boolean", example="true"),
 *                      @OA\Property(property="color", type="string", example="#000000"),
 *                       @OA\Property(property="font", type="string", example="Gibson"),
 *                       @OA\Property(property="font_weight", type="string", example="bold"),
 *                                        )) 
 *                                   ),          
 *              ),
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),
 *
 */
/**
 * @OA\get(
 * path="/graph/new_followers/{period}/{rate}",
 * summary="get the number of the new followers",
 * description="get the number of the new followers for the activity graph",
 * tags={"Activity"},
 * operationId="getNewFollwersActivityGraph",
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="period",
 *          description="the time period that you want to retrieve the data for.
 *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="rate",
 *          description="the time rate that you want to retrieve the data with.
 *  ( 0 -> hourly) , (1 -> daily),
 *  note: if the period=1, then the rate must equal 0.",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="notes_count", type="integer", example=16),
 *       @OA\Property(property="new_followers_count", type="integer", example=6),
 *       @OA\Property(property="total_followers_count", type="integer", example=326),
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="new_followers", type="integer", example=5),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="new_followers", type="integer", example=7),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="new_followers", type="integer", example=2),),
 *          )),
 *       @OA\Property(property="top_post", type="object", 
 *              @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *              @OA\Property(property="total_notes_count", type="integer", example=14),
 *              @OA\Property(property="notes_count", type="integer", example=2),
 *              ),
 *       @OA\Property(property="biggest_fan", type="array",
 *          @OA\Items(
 * 
 *               @OA\Property(property="name", type="string", example="mycppblog"),
 *               @OA\Property(property="blog_id", type="int", example="123456789"),
 *               @OA\Property(property="blog_username", type="int", example="123456789"),
 *              @OA\Property(property="blog_avatar", type="string", example="storage/blogs/avatar2125"),    
 *              @OA\Property(property="blog_avatar_shape", type="string", example="square"), 
 *               @OA\Property(property="followed", type="bool", example=true),
 *               @OA\Property(property="theme_id", type="int", example="123456789"),
 *               @OA\Property(property="title", type="array",
 *                   @OA\Items(
 *                       @OA\Property(property="text", type="string", example="CPP Programming"),
 *                       @OA\Property(property="show", type="boolean", example="true"),
 *                      @OA\Property(property="color", type="string", example="#000000"),
 *                       @OA\Property(property="font", type="string", example="Gibson"),
 *                       @OA\Property(property="font_weight", type="string", example="bold"),
 *                                        )) 
 *                                   ),          
 *              ),
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),
 *
 */
/**
 * @OA\get(
 * path="/graph/total_followers/{period}/{rate}",
 * summary="get the total number of followers",
 * description="get the total number of followers for the activity graph",
 * tags={"Activity"},
 * operationId="getTotalFollwersActivityGraph",
 * security={ {"bearer": {} }},
 *  @OA\Parameter(
 *          name="period",
 *          description="the time period that you want to retrieve the data for.
 *  ( 1 -> last day) , (3 -> last 3  days) , (7 -> last week) , (30 -> last month)",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 *  @OA\Parameter(
 *          name="rate",
 *          description="the time rate that you want to retrieve the data with.
 *  ( 0 -> hourly) , (1 -> daily),
 *  note: if the period=1, then the rate must equal 0.",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="notes_count", type="integer", example=16),
 *       @OA\Property(property="new_followers_count", type="integer", example=6),
 *       @OA\Property(property="total_followers_count", type="integer", example=326),
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="total_followers", type="integer", example=100),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="total_followers", type="integer", example=156),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="total_followers", type="integer", example=304),),
 *          )),
 *       @OA\Property(property="top_post", type="object", 
 *              @OA\Property(property="post_body", type="general", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *              @OA\Property(property="total_notes_count", type="integer", example=14),
 *              @OA\Property(property="notes_count", type="integer", example=2),
 *              ),
 *       @OA\Property(property="biggest_fan", type="array",
 *          @OA\Items(
 * 
 *               @OA\Property(property="name", type="string", example="mycppblog"),
 *               @OA\Property(property="blog_id", type="int", example="123456789"),
 *               @OA\Property(property="blog_username", type="int", example="123456789"),
 *              @OA\Property(property="blog_avatar", type="string", example="storage/blogs/avatar2125"),    
 *              @OA\Property(property="blog_avatar_shape", type="string", example="square"), 
 *               @OA\Property(property="followed", type="bool", example=true),
 *               @OA\Property(property="theme_id", type="int", example="123456789"),
 *               @OA\Property(property="title", type="array",
 *                   @OA\Items(
 *                       @OA\Property(property="text", type="string", example="CPP Programming"),
 *                       @OA\Property(property="show", type="boolean", example="true"),
 *                      @OA\Property(property="color", type="string", example="#000000"),
 *                       @OA\Property(property="font", type="string", example="Gibson"),
 *                       @OA\Property(property="font_weight", type="string", example="bold"),
 *                                        )) 
 *                                   ),          
 *              ),
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "401", "msg":"Unauthorized"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=403,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),
 *
 */
 }