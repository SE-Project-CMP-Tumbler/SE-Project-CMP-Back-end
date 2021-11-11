<?php

namespace App\Http\Misc\Traits;

trait WebServiceResponse
{
    // ok this is not found??
    public function error_response($error, $code = 422)
    {
    	return $this->general_response("", $error, $code);
    }
    public function general_response($data = "", $msg = "", $status_code = "200")
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
