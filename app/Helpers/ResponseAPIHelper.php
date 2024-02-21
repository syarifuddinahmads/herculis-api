<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;

trait ResponseAPIHelper
{
    public static function sendSuccess($data = null, string $message = 'Success', int $statusCode = ResponseInterface::HTTP_OK)
    {
        return self::sendResponse(true, $message, $data, $statusCode);
    }

    public static function sendError($message = 'Error', $data = null, int $statusCode = ResponseInterface::HTTP_BAD_REQUEST)
    {
        return self::sendResponse(false, $message, $data, $statusCode);
    }

    protected static function sendResponse(bool $success, string $message, $data = null, int $statusCode)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        return service('response')->setStatusCode($statusCode)->setJSON($response);
    }
}
