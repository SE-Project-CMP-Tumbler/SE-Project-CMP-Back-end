<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Misc\Helpers\Errors;

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
  * delete access_token for a user
  * @param User $user
  * @return bool
 */
    public function logout(User $user)
    {
        if (!$user || !($user->token())) {
            return false;
        }
        $user->token()->delete();
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
}
