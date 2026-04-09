<?php

class Response
{
    public static function success($data = null, $message = null, $code = 200)
    {
        http_response_code($code);

        echo json_encode([
            "status" => "success",
            "message" => $message,
            "data" => $data
        ]);
    }

    public static function error($message = "Internal Server Error", $code = 500)
    {
        http_response_code($code);

        echo json_encode([
            "status" => "error",
            "message" => $message
        ]);
    }
}