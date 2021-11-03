<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
/**
 * @OA\Post(
 * path="/register",
 * summary="Signup a new user",
 * description=" Creating a secondary blog",
 * tags={"User"},
 * operationId="signupuser",
 * 
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : The email of the new user ,
 *    blog_username : The blog_username will be used in the primary blog,
 *    password : The password of the new user",
 *    @OA\JsonContent(
 *      required={"email","blog_username","password"},
 *      @OA\Property(property="email", type="string", example="user2023@gmail.com"),
 *      @OA\Property(property="blog_username", type="string", example="CairoBlogs"),
 *      @OA\Property(property="password", type="string",format="password", example="<df1212V>"),
 *                )
 *               ),
 * 
 * 
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="id", type="string", example="12151"),
 *       @OA\Property(property="blog_username", type="string", example="MyFirstBlog"),
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),   
 *       @OA\Property(property="blog_avatar", type="string", format="byte",example="/storage/mypicture.extension"),
 *       @OA\Property(property="api_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),   
 *         ),
 *        )
 *     ),
*  @OA\Response(
 *    response=422,
 *    description="Unprocessable Entity",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"Unprocessable Entity"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"error"})
 *        )
 *     )
 * ),
 *  * @OA\Post(
 * path="/login",
 * summary="login user",
 * description="user login",
 * tags={"User"},
 * operationId="loginuser",
*   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : The email of the user ,
 *    password : The password of the user",
 *    @OA\JsonContent(
 *      required={"email","password"},
 *      @OA\Property(property="email", type="string", example="user2023@gmail.com"),
 *      @OA\Property(property="password", type="string",format="password", example="<df1212V>"),
 *                )
 *               ),
 * @OA\Response(
 *    response=200,
 *    description="Successful credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="id", type="string", example="12151"),
 *       @OA\Property(property="blog_username", type="string", example="MyFirstBlog"),
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),   
 *       @OA\Property(property="blog_avatar", type="string", format="byte",example="/storage/mypicture.extension"),
 *       @OA\Property(property="api_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),   
 *         ),
 *        )
 *     ),
*  @OA\Response(
 *    response=404,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"error"})
 *        )
 *     )
 * ),
 */


}
