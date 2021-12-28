<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use App\Models\Blog;
use App\Models\Theme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Misc\Helpers\Errors;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;

class UserService
{
 /**
  * Create a new user
  * @param string $email
  * @param string $password
  * @param int $age
  * @param bool $is_linked_by_google
  * @param string $username
  * @param string $google_id (optional)
  * @return User
 */
    public function register(string $email, string $password, int $age, bool $is_linked_by_google, string $username, string $google_id = null)
    {
        if (($is_linked_by_google && !($google_id))   ||    (! $is_linked_by_google && ($google_id))) {
            return null;
        }

        $password = $is_linked_by_google ? ($email . $google_id . '@tumbler_default_password' . $email) : $password;
        DB::beginTransaction();
        try {
            $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'age' => $age,
            'linked_by_google' => $is_linked_by_google,
            'google_id' => $google_id
            ]);
            /* call service create blog */
            $blog = Blog::create([
                'username' => $username,
                'title' => $username,
                'is_primary' => true,
                'user_id' => $user->id,
            ]);
            Theme::create(['blog_id' => $blog->id]);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
             return null;
        }

        if ($is_linked_by_google) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        } else {
            event(new Registered($user));
        }

        return $user;
    }
 /**
  * check the login credentials
  * @param string $email
  *@param string $password
  * @return User
 */
    public function checkLoginCredentials(string $email, string $password)
    {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            $user = null;
        }
        return $user;
    }

 /**
  * grant access_token for a user
  * @param User $user
  * @return bool
 */
    public function grantAccessToken(User $user)
    {
        if (!$user) {
            return false;
        }
        $token = $user->createToken('Auth Token')->accessToken;
        $user->withAccessToken($token);
        return true;
    }
 /**
  * either verify email or resend verification mail for an unverified user
  * @param User $user
  * @param bool $resend
  * @return bool
 */
    public function verifyUserEmail(User $user, bool $resend)
    {
        if (!$user) {
            return false;
        }
        if (! $user->hasVerifiedEmail()) {
            if ($resend) {
                $user->sendEmailVerificationNotification();
                return true;
            }
            $user->markEmailAsVerified();
            event(new Verified($user));
            return true;
        }
        return false;
    }
 /**
  * check the magic link arguments
  * @param int $id
  * @param string $hash
  * @return bool
 */
    public function matchMagicLinkHash($id, $hash)
    {
        $user = null;
        if (is_numeric($id)) {
            $user = User::find($id);
        }

        if (
            !$user || (
            ! hash_equals(
                $hash,
                sha1($user->getEmailForVerification())
            ) )
        ) {
            return false;
        }

        return true;
    }
 /**
  * check the password link arguments
  * @param int $id
  * @param string $token
  * @return string
 */
    public function matchResetPasswordLink($id, $token)
    {
        if (!is_numeric($id)) {
            return  null;
        }
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        if (!(Password::tokenExists($user, $token))) {
            return null;
        }
            return $user->getEmailForPasswordReset();
    }
 /**
  * reset password
  *this function receives an array of argument to ('email', 'password', 'password_confirmation', 'token')
  *then tries to reset the user's password
  * @param array $arguments
  * @return string
 */
    public function resetPassword($arguments)
    {
        $status = Password::reset(
            $arguments,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status;
    }
     /**
  * Check unique email of user
  * @param string $email
  * @return boolean
 */

    public function uniqueEmail(string $email)
    {
        if (User::where('email', $email)->count() > 0) {
            return false;
        }
        return true;
    }
     /**
  * get the google_id and email of a certain user
  * @param string $googleAccessToken
  * @return array
 */

    public function getGoogleData(string $googleAccessToken)
    {
        $google_id = "";
        $email = "";
        try {
            $user = Socialite::driver('google')->userFromToken($googleAccessToken);
            $google_id = $user->id;
            $email = $user->email;
        } catch (\Throwable $th) {
            return null;
        }
        return ["google_id" => $google_id , "email" => $email];
    }
 /**
  * check the login with google credentials
  * @param string $email
  *@param string $google_id
  * @return User
 */
    public function checkGoogleLoginCredentials(string $email, string $google_id)
    {

        $user = User::where('email', $email)->first();
        if (!$user || !$user->linked_by_google || $user->google_id != $google_id) {
            $user = null;
        }
        return $user;
    }
}
