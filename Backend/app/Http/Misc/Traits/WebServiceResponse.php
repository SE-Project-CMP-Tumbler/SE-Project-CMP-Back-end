<?php

namespace App\Http\Misc\Traits;

trait WebServiceResponse
{

    private function general_response($data = "", $mgs = "", $status_code = "200")
    {
        if ($data != "") {
            return response()->json([
                "response"          => $data,
                "meta"         => [ "status" => $status_code, "msg" => $msg]
            ]);
        } else {
            return response()->json([
            "meta"         => [ "status" => $status_code, "msg" => $msg]
            ]);
        }
    }
}
