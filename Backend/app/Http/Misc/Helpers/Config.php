<?php

/**
 *  external resource from  https://github.com/SiliconArena/alphamart-backend
*/

namespace App\Http\Misc\Helpers;

class Config
{
    public const PAGINATION_LIMIT = 10;
    public const PAGINATION_LIMIT_MIN = 12;
    public const API_PAGINATION_LIMIT = 10;
    public const MIN_GPS_RADIUS = 0.0;
    public const MAX_GPS_RADIUS = 0.3;
    public const CACHE_TTL = 60 * 60; // 60seconds * 60 min (1hour)
    public const EXPIRE = 10;
    public const TOKEN = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWVhOWE2NDYwYmIwODU0ODhmMWE4MjJlNTdmMDdhMWM1NWRiNjkzYjc5NzI4NDg4M2UzM2MzMmY0MjlkODI1MzE0YTcwMDIyMmI4NGJkNjIiLCJpYXQiOjE2MzcyNzAyOTkuNzc2ODc0LCJuYmYiOjE2MzcyNzAyOTkuNzc2ODc5LCJleHAiOjE2Njg4MDYyOTkuNjkwMTk4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.AqQ0Sa9H9FXQpuDYhz3PDhG8YGJ1aihEAF9YPNNDnWkN3XOC9j0oqLyUzMBahwkocrVADFYHU78fTfSdDUyE1YyFLzlwURGXiEV7m1kes2zOmBTMMoFCkXAufz_F94yJPovqF2CYoZJy6jnGyHl0Gsr3ag8uEqSet7RmFLU7r28LmMfU9aQRbjIAZUnpYy7ygFryf9Wx9hOf3EXkkjn2pcsNUnMwhfMCOddaS3P_d7KJQD2x5JnV_fj7oM_JrTFsd4HHjPWzFIzds7YLN80KRXYo82Q52Es9VpxVj3F1e013YWl2H5677tO__G-Gt8MOCdphiVLIb515piOTnc8ik9QA0Pjb40soJ-PRRi6lL3lstxV9pAXd75BL3F2TjTcPhPukqDoNxU-ZtubiYs2CBPo2Jp_ceWsY7NHV4gwI7rPZgTlVLJYEyDuybnLWmfnWbOk8FgkVOgvMvngctl3uQQTWJx5TITiSOB_pA7IqCmb17x1hwTPMDFo5vReE7mfeT54WhK-g8-m6B2blcg2BcpwnJSzTiNd5ntzG3R8eiy9nzYxf9cHB1dbkr3cW4-pm_pG8xMDTHyE_Cc1LX7oCGxYzR0lNQWGS3dai0UXctfg5r6WPvjHlyM_j3elEZXz7aW4vieEHtzFdj3N-uf_emDE8vF3hYGz0gRjwaImYloY";
    public const JSON = ['Accept' => 'application/json','Content-Type' => 'application/json'];

    public const FILE_UPLOAD_MAX_SIZE = 102400; // 100MB

    public const VALID_IMAGE_TYPES = ["jpg", "jpeg", "png", "gif", "bmp", "tif"];
    public const VALID_AUDIO_TYPES = ["mp3","wav","3gp","3gpp"];

    // if laravel can fake the video type you're adding, add it as first element and increase the length
    public const VALID_FAKE_LEN = 5;
    public const VALID_VIDEO_TYPES = ["mp4", "mkv", "mov", "avi", "webm"];

    public const NOT_VALID_IMAGE_TYPES = ["psd", "pdf", "eps", "indd", "ai", "raw", "cr2", "nef", "orf", "sr2"];
    public const NOT_VALID_AUDIO_TYPES = ["m4a", "flac", "mp4", "wma", "aac", "ogg"];

    // if laravel can fake the video type you're adding, add it as first element and increase the length
    public const NOT_VALID_FAKE_LEN = 2;
    public const NOT_VALID_VIDEO_TYPES = ["flv", "swf", "f4v"];

    // avaliable post status and types
    public const POST_TYPES = ['ask', 'general', 'text', 'audio', 'video', 'chat', 'link', 'image', 'quote'];
    public const POST_STATUS_TYPES = ['published', 'draft', 'private', 'submission'];
    //
    public const DEFAULT_AVATAR = "https://cdnb.artstation.com/p/assets/images/images/033/268/113/large/edmerc-d-mercadal-eren-v5.jpg?1609001111";
    public const DEFAULT_HEADER_IMAGE = "https://cdna.artstation.com/p/assets/images/images/011/360/222/large/moraya-magdy-maro-45-1.jpg?1529180970";
    public const TAG_IMAGE  = "https://cdnb.artstation.com/p/assets/images/images/028/144/455/large/javier-pedreno-dutch-post-9.jpg?1593610613";
}
