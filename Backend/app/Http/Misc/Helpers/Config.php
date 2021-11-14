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
}
