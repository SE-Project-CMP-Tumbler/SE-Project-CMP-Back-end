<?php

namespace App\Http\Misc\Helpers;

class Config
{
    const PAGINATION_LIMIT = 15;
    const PAGINATION_LIMIT_MIN = 12;
    const API_PAGINATION_LIMIT = 10;
    const MIN_GPS_RADIUS = 0.0;
    const MAX_GPS_RADIUS = 0.3;
    const CACHE_TTL = 60 * 60; // 60seconds * 60 min (1hour)
    const EXPIRE = 10;
    const TOKEN = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWVhOWE2NDYwYmIwODU0ODhmMWE4MjJlNTdmMDdhMWM1NWRiNjkzYjc5NzI4NDg4M2UzM2MzMmY0MjlkODI1MzE0YTcwMDIyMmI4NGJkNjIiLCJpYXQiOjE2MzcyNzAyOTkuNzc2ODc0LCJuYmYiOjE2MzcyNzAyOTkuNzc2ODc5LCJleHAiOjE2Njg4MDYyOTkuNjkwMTk4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.AqQ0Sa9H9FXQpuDYhz3PDhG8YGJ1aihEAF9YPNNDnWkN3XOC9j0oqLyUzMBahwkocrVADFYHU78fTfSdDUyE1YyFLzlwURGXiEV7m1kes2zOmBTMMoFCkXAufz_F94yJPovqF2CYoZJy6jnGyHl0Gsr3ag8uEqSet7RmFLU7r28LmMfU9aQRbjIAZUnpYy7ygFryf9Wx9hOf3EXkkjn2pcsNUnMwhfMCOddaS3P_d7KJQD2x5JnV_fj7oM_JrTFsd4HHjPWzFIzds7YLN80KRXYo82Q52Es9VpxVj3F1e013YWl2H5677tO__G-Gt8MOCdphiVLIb515piOTnc8ik9QA0Pjb40soJ-PRRi6lL3lstxV9pAXd75BL3F2TjTcPhPukqDoNxU-ZtubiYs2CBPo2Jp_ceWsY7NHV4gwI7rPZgTlVLJYEyDuybnLWmfnWbOk8FgkVOgvMvngctl3uQQTWJx5TITiSOB_pA7IqCmb17x1hwTPMDFo5vReE7mfeT54WhK-g8-m6B2blcg2BcpwnJSzTiNd5ntzG3R8eiy9nzYxf9cHB1dbkr3cW4-pm_pG8xMDTHyE_Cc1LX7oCGxYzR0lNQWGS3dai0UXctfg5r6WPvjHlyM_j3elEZXz7aW4vieEHtzFdj3N-uf_emDE8vF3hYGz0gRjwaImYloY";
    const JSON = ['Accept' => 'application/json','Content-Type' => 'application/json'];
}
