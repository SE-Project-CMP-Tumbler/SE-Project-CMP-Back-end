<?php

namespace App\Http\Misc\Traits;

trait WebServiceResponse
{
    // ok this is not found??
    public function errorResponse($error, $code = 422)
    {
        return $this->generalResponse("", $error, $code);
    }
    public function generalResponse($data = "", $msg = "", $status_code = "200")
    {
        if ($data != "") {
            return response()->json([
                "response"          => $data,
                "meta"         => [ "status" => $status_code, "msg" => $msg]
            ], $status_code);
        } else {
            return response()->json([
            "meta"         => [ "status" => $status_code, "msg" => $msg]
            ], $status_code);
        }
    }
}
