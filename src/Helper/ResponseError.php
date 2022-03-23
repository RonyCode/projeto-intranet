<?php

namespace Api\Helper;

trait ResponseError
{
    public function responseCatchError(string $message)
    {
        http_response_code(404);
        echo json_encode(
            [
                'data' => false,
                'status' => 'error',
                'code' => 404,
                'message' => $message
            ],
            JSON_UNESCAPED_UNICODE
        );
        exit;
    }
}
