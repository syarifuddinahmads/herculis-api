<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;

trait ResponseAPIHelper
{
    public static function sendSuccess($data = null,  $message = 'Success',  $statusCode = ResponseInterface::HTTP_OK)
    {
        return self::sendResponse(true, $message, $data, $statusCode);
    }

    public static function sendError($message = 'Error', $data = null,  $statusCode = ResponseInterface::HTTP_BAD_REQUEST)
    {
        return self::sendResponse(false, $message, $data, $statusCode);
    }

    protected static function sendResponse( $success,  $message, $data = null,  $statusCode=null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        return service('response')->setStatusCode($statusCode)->setJSON($response);
    }
}
