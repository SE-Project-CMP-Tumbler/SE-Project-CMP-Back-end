<?php

/**
 *  external resource from https://github.com/SiliconArena/alphamart-backend
*/

namespace App\Http\Misc\Helpers;

class Errors
{
    // auth errors
    const COMPLETED_PROFILE_BEFORE = 'you have completed your profile before.';
    const COMPLETED_PROFILE_MUST = 'you must complete your profile.';
    const NOT_FOUND_USER  = 'There is no account associated with this email.';
    const WRONG_PASSWORD  = 'Invalid login, wrong email or password.';
    const NOT_VERIFIED_USER = 'Non Verified User.';
    const NO_FOLLOW = 'you cannot unfollow a non followed user.';

    //signup errors (errors are ordered W.R.T it's priorities)
    const MISSING_EMAIL = 'You forgot to enter your email!';
    const MISSING_PASSWORD = 'You forgot to enter your password!';
    const MISSING_BLOGNAME = 'You forgot to enter your blog name!';
    const MISSING_AGE = 'your age is required.';
    const NOT_VALID_EMAIL = 'That is not a valid email address. Please try again.';
    const EMAIL_TAKEN = 'This email address is already in use.';
    const SHORT_PASSWORD = 'The password must be at least 8 characters.';
    const MISSING_BLOG_USERNAME = 'That is not a valid blog name. Someone beat you to that username.';
    const PASSWORD_SHORT = 'The password must be at least 8 characters.';
    const MIN_AGE  = 'Your age must be at least 13 YO. ';
    const INVALID_AGE  = 'Please enter a valid age. ';

    //signin errors (errors are ordered W.R.T it's priorities + missing only mail or only password errors are the same as the signup errors)
    const MISSING_BOTH_EMAIL_PASSWORD = 'You do have to fill this stuff out, you know.';
    const INCORRECT_EMAIL_PASSWORD = 'Your email or password were incorrect.';

    //reset password errors
    const MISSING_PASSWORD_CONFORMATION = 'you password does not match the password confirmation';

    //login with google errors
    const NOT_LINKED_BY_GOOGLE = 'your account already exists, but not linked';

    //change password errors
    const INVALID_CHANGE_PASSWORD = 'Invalid password entered';
    const INVALID_CHANGE_PASSWORD_CONFORMATION = "Password don't match";
    const MISSING_CHANGE_PASSWORD = 'Password is empty';

    //records errors
    const EXISTS = "Record already exists!";
    const NOT_EXISTS = "Record not exists!";

    // general errors
    const TESTING  = 'Not Found';
    const UNAUTHENTICATED  = 'Forbidden';
    const UNAUTHORIZED = 'Unauthorized.';
    const GENERAL = "Internal Server error";
    const NOTALLOWED = "Method Not Allowed";
    const INVALID_EMAIL_VERIFICATION_URL = "that email is invalid to verification";
    const EMAIL_ALREADY_VERIFIED = 'api.email_already_verified';

    //sent email
    const CODE = "Invalid code!";
}
