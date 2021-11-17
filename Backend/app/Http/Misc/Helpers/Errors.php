<?php

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
