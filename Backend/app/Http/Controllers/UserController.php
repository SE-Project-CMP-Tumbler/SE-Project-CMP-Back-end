<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Misc\Traits\WebServiceResponse;
use Facade\FlareClient\Http\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Services\UserService;
use App\Services\BlogService;

class UserController extends Controller
{
/**
 * @OA\Post(
 * path="/register",
 * summary="Signup a new user",
 * description=" Creating a new user",
 * tags={"User"},
 * operationId="signupuser",
 *
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : The email of the new user ,
 *    blog_username : The blog_username will be used in the primary blog,
 *    password : The password of the new user,
 *    age : The age of the new user",
 *    @OA\JsonContent(
 *      required={"email","blog_username","password","age"},
 *      @OA\Property(property="email", type="string", example="user2023@gmail.com"),
 *      @OA\Property(property="blog_username", type="string", example="CairoBlogs"),
 *      @OA\Property(property="password", type="string",format="password", example="<df1212V>"),
 *      @OA\Property(property="age", type="string", example="22"),
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
 */
 /**
  * Create a new user
  * @param Request $request
  * @return Json
 */
    public function register(UserRegisterRequest $request)
    {
        $userService = new UserService();
        $blogService = new BlogService();
        $unique = $blogService->uniqueBlog($request->blog_username);
        if (!$unique) {
            return $this->error_response(Errors::MISSING_BLOG_USERNAME, '404');
        }
        $user = $userService->register(
            $request->email,
            $request->password,
            $request->age,
            false,
            $request->blog_username
        );
        if (!$user) {
            return $this->error_response('not found', '404');
        }

        $userService->grantAccessToken($user);

        return $this->general_response(new UserResource($user), "Successful response", "200");
    }
/** @OA\Post(
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
 *    description="not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),
 */
 /**
  * login a user
  * @param Request $request
  * @return Json
 */
    public function login(UserLoginRequest $request)
    {
        $userService = new UserService();
        $user = $userService->checkLoginCredentials($request->email, $request->password);
        if (!$user) {
            return $this->error_response(Errors::INCORRECT_EMAIL_PASSWORD, '422');
        }
        $userService->grantAccessToken($user);
        return $this->general_response(new UserResource($user), "Successful response", '200');
    }
/** @OA\Post(
 * path="/login_with_google",
 * summary="login a user",
 * description=" login using google ",
 * tags={"User"},
 * operationId="loginuserwithgoogle",
 *
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    google_access_token : The access_token of the user's gmail account",
 *    @OA\JsonContent(
 *      required={"google_access_token"},
 *      @OA\Property(property="google_access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),
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
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),
 */
/** @OA\Post(
 * path="/register_with_google",
 * summary="Register a new user",
 * description="Creating a new user using google",
 * tags={"User"},
 * operationId="signupuserwithgoogle",
 *
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    google_access_token : The access_token of the user's gmail account,
 *    blog_username : The blog_username will be used in the primary blog,
 *    age : The age of the new user",
 *    @OA\JsonContent(
 *      required={"google_access_token","blog_username","age"},
 *      @OA\Property(property="google_access_token", type="string", example="IRN6UNk4bIDqStMb6OkfF6lYCIMufnEoJQZkE0wo"),
 *      @OA\Property(property="blog_username", type="string", example="CairoBlogs"),
 *      @OA\Property(property="age", type="string", example="22"),
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
 */
/** @OA\Post(
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
 *     )
 * ),
 */
 /**
  * logout a specific user device (delete a specific token not all user tokrns)
  * @param Request $request
  * @return Json
 */
    public function logout(Request $request)
    {
        if ((new UserService())->logout($request->user())) {
            return $this->general_response('', "Successful response", '200');
        }
        return $this->error_response('not found', '404');
    }
/** @OA\get(
 * path="/email/verify/{id}/{hash}",
 * summary="email verification",
 * description="the page that helps the user to create a new password",
 * tags={"User"},
 * operationId="verifyemail",
 *  @OA\Parameter(
 *          name="id",
 *          description="the id of the user ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *  @OA\Parameter(
 *          name="hash",
 *          description="the hash of the user ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"})
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
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
 */
 /**
  * verify a user's email
  * @param Integer $id
  * @param String $hash
  * @return Json
 */
    public function emailVerification($id, $hash)
    {
        $userService = new UserService();
        $user = User::find($id);
        if (
            !($userService->matchMagicLinkHash($id, $hash)) ||
            !($userService->verifyUserEmail($user, false))
        ) {
            return $this->error_response('not found', '404');
        }

        return $this->general_response("", "Successful response", '200');
    }


/** @OA\Post(
 * path="/email/resend_verification",
 * summary="resend verification email",
 * description="resend verification email to the email",
 * tags={"User"},
 * security={ {"bearer": {} }},
 * operationId="resendverificationemail",
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *        )
 *     ),
 *  @OA\Response(
 *    response=404,
 *    description="not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
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
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),
 */
 /**
  * resend a user's mail verification
  * @param Request $request
  * @return Json
 */
    public function resendVerification(Request $request)
    {
        if ((new UserService())->verifyUserEmail($request->user(), true)) {
            return $this->general_response("", "Successful response", '200');
        }
        return $this->error_response("not found", '404');
    }
/** @OA\Post(
 * path="/forgot_password",
 * summary="sending password reset email",
 * description="sending an email with a password reset link to the user",
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
 *    description="Not found response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"not found"})
 *        )
 *     )
 * ),
 */
/** @OA\get(
 * path="/reset_password/{id}/{access_token}",
 * summary="entering a new password",
 * description="the page that helps the user to create a new password",
 * tags={"User"},
 * operationId="enternewpassword",
 *  @OA\Parameter(
 *          name="id",
 *          description="the id of the user ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *  @OA\Parameter(
 *          name="access_token",
 *          description="the access_token of the user ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
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
 *    description="Not found",
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
 */
/** @OA\Post(
 * path="/reset_password",
 * summary="reset password",
 * description="reseting the user's password",
 * tags={"User"},
 * security={ {"bearer": {} }},
 * operationId="resetpassword",
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : this is the same email that the user used to signup and also for recieving the verification email ,
 *    password : this is the new password,
 *    password_confirmation : the password of the new password ",
 *    @OA\JsonContent(
 *      required={"email","password","password_confirmation"},
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
 */

/** @OA\Put(
 * path="/change_password",
 * summary="Change password of user",
 * description=" Change a password of user",
 * tags={"User"},
 * operationId="changepassword",
 *  security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    password : The password of the  user,
 *    password_confirmation : The password of the user",
 *    @OA\JsonContent(
 *      required={"password","password_confirmation"},
 *      @OA\Property(property="password", type="string", format="password", example="123"),
 *      @OA\Property(property="password_confirmation", type="string",  format="password", example="123"),
 *                )
 *               ),
 *
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *
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
 /** @OA\Put(
 * path="/change_email",
 * summary="Change email of user",
 * description=" Change a email of user",
 * tags={"User"},
 * operationId="changeemail",
 *  security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    password : The password of the user,
 *    email : The  email of the  user",
 *    @OA\JsonContent(
 *      required={"password","email"},
 *      @OA\Property(property="password", type="string", format="password", example="123"),
 *      @OA\Property(property="email", type="string",  format="email", example="radwa@gmail.com"),
 *                )
 *               ),
 *
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
 *       @OA\Property(property="response", type="object",
 *           @OA\Property(property="email",type="string",format="email",example="radwa@gmail.com"),
 *         ),
 *        )
 *     ),
 *    @OA\Response(
 *    response=422,
 *    description="Unprocessable Entity",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "422", "msg":"Unprocessable Entity"})
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
 *    response=500,
 *    description="Internal Server error",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "500", "msg":"Internal Server error"})
 *        )
 *     )
 * ),
 *
 */

/** @OA\Delete(
 * path="/delete_user",
 * summary="delete the user",
 * description=" delete the user account",
 * tags={"User"},
 * operationId="deleteuser",
 *  security={ {"bearer": {} }},
 *   @OA\RequestBody(
 *    required=true,
 *    description=  "
 *    email : The  email of the  user,
 *    password : The password of the user",
 *    @OA\JsonContent(
 *      required={"password","email"},
 *      @OA\Property(property="email", type="string",  format="email", example="user2023@gmail.com"),
 *      @OA\Property(property="password", type="string", format="password", example="<syudhguin21215>"),
 *                )
 *               ),
 *
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful response",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
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
 *    description="not found",
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
