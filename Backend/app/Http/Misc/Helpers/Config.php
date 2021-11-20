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
    const TOKEN = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWMyNjU3YzMyMzcwNjY2MGE5M2E4MzcwOGJhZWUwYjgyMjkyZWM2YmE5ZWRlYmYzNzBjMWQ3MTMwNTdhOWQxMWFhMjAyNWMwOTVmZmMyYjYiLCJpYXQiOjE2MzczMzEwNTEuNzAzMjYsIm5iZiI6MTYzNzMzMTA1MS43MDMyNjUsImV4cCI6MTY2ODg2NzA1MS41MTMyMywic3ViIjoiMTIiLCJzY29wZXMiOltdfQ.OdvmqYvMQWSv0lnzRr4utGOmeYAlAEd2m6asGuWLyM6UYTx0Tz4_t57Rab50JA05wrdFIvj06uqnyNewgRFiZyolcR2G6kfXquaK7X7WmuKspbzcVGip6ZBIZVYRsFAu5w07gl3ecYAVAmRpDKi-r35lCKhIr_W224g7Z3dBob3IEIug2HnpZ0gCFIN2-AXWK-96m8NVsvZ5dVtOhFyS76kp1TuwYhwz_qjT_5q330IuPjILJGlphTnZlScZfzkahoN7-JBaIiKUckpt64KPHnFilbtV4BaxV9rXlRqTCWbwnnX7uWu119i3EmWTap5d3_6cMTxRn-jvPYG43vqlYBV1lnrlrnp3X0G9idAbwUleIFFm7QVjJzuqovNyWkVUbdXAuS88qMxszy5pqWNpRrMzRwltzFiXLZWf2NBWGWobs3-2h17_0cSTBgNLPLivSasCZ9sy4emF08Vh1qtOztEEK5IZm8sjSsB7E90mqZKwpZt2uoE_uNjN3i-zEBxn24DBZF8nQptcAx9ityR5u3EPgJTjomHzChz0Ue4v_452zqGJKlQuwXsEI84YV-5gy5sgBInVTkRvyEnZTxsP_opPrMkU8cCPKY279V8hDD1Gcv766CkLMs5-QKTU-ZqIZSmsSc7SFfY-CZE4mT2Z4j5HJcSNrdjBqyQ9vKu4YhI";
    const JSON = ['Accept' => 'application/json','Content-Type' => 'application/json'];
}
