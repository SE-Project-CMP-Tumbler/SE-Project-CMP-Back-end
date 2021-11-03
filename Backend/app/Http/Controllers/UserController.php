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
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="id", type="string", example="12151"),
 *       @OA\Property(property="blog_username", type="string", example="MyFirstBlog"),
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),   
 *       @OA\Property(property="blog_avatar", type="string", format="byte",example="/storage/mypicture.extension"),
 *       @OA\Property(property="access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),   
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
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),

 *
* @OA\Post(
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
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="id", type="string", example="12151"),
 *       @OA\Property(property="blog_username", type="string", example="MyFirstBlog"),
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),   
 *       @OA\Property(property="blog_avatar", type="string", format="byte",example="/storage/mypicture.extension"),
 *       @OA\Property(property="access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),   
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),

 * 
* @OA\Post(
 * path="/logout",
 * summary="logout user",
 * description="user logout",
 * tags={"User"},
 * security={ {"bearer": {} }},
 * operationId="logoutuser",
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),
 
* @OA\Post(
 * path="/forgot-password",
 * summary="email verification",
 * description="sending email verification link to the user",
 * tags={"User"},
 * operationId="emailverification",
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : this is the same email that the user used to signup ",
 *    @OA\JsonContent(
 *      required={"email"},
 *      @OA\Property(property="email", type="string", example="user2023@gmail.com"),
 *                )
 *               ),
 * 
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),
 * 
* @OA\get(
 * path="/reset_password/{access_token}",
 * summary="entering a new password",
 * description="the page that helps the user to create a new password",
 * tags={"User"},
 * operationId="enternewpassword",
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),      
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"}),
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
* @OA\Post(
 * path="/reset_password",
 * summary="reset password",
 * description="reseting the user's password",
 * tags={"User"},
 * security={ {"bearer": {} }},
 * operationId="resetpassword",
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    access_token : this is the token that was sent to the user via email in the verification link,
 *    email : this is the same email that the user used to signup and also for recieving the verification email ,
 *    password : this is the new password,
 *    password_confirmation : the password of the new password ",
 *    @OA\JsonContent(
 *      required={"access_token","email","password","password_confirmation"},
 *      @OA\Property(property="access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),
 *      @OA\Property(property="email", type="string", example="user2023@gmail.com"),
 *      @OA\Property(property="password", type="string",format="password", example="CMP21520cmp>"),
*      @OA\Property(property="password_confirmation", type="string",format="password", example="CMP21520cmp>"),
 *                )
 *               ),
 * 
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response",type="object",
 *       @OA\Property(property="id", type="string", example="12151"),
 *       @OA\Property(property="blog_username", type="string", example="MyFirstBlog"),
 *       @OA\Property(property="email", type="string", example="user2023@gmail.com"),   
 *       @OA\Property(property="blog_avatar", type="string", format="byte",example="/storage/mypicture.extension"),
 *       @OA\Property(property="access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),   
 *         ),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Wrong response",
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
 */


}