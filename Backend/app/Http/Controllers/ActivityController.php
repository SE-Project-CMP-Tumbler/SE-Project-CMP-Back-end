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
 * operationId="getactivitygraphnotes",
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
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=5),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=7),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="Notes", type="integer", example=2),
 *              @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *          )),
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
 * summary="get the notes",
 * description="get the notes for the activity graph",
 * tags={"Activity"},
 * operationId="getactivitygraphnotes",
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
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=5),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=7),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="Notes", type="integer", example=2),
 *              @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *          )),
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
 * path="/graph/notes/{period}/{rate}",
 * summary="get the notes",
 * description="get the notes for the activity graph",
 * tags={"Activity"},
 * operationId="getactivitygraphnotes",
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
 *       @OA\Property(property="data", type="array",
 *           @OA\Items(
 *               @OA\Property(property="0", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-03 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=5),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *              @OA\Property(property="1", type="object",
 *                @OA\Property(property="timestamp", type="string", example="2021-11-04 01:13:39"),
 *                @OA\Property(property="Notes", type="integer", example=7),
 *                @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *           @OA\Property(property="2", type="object",
 *              @OA\Property(property="timestamp", type="string", example="2021-11-05 01:13:39"),
 *              @OA\Property(property="Notes", type="integer", example=2),
 *              @OA\Property(property="top_post", type="string", example="<div><h1>What's Artificial intellegence? </h1><img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'>Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),),
 *          )),
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
