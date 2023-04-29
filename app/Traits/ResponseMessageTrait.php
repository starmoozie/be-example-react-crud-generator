<?php

namespace App\Traits;

use App\Constants\HttpCode;

/**
 * Json Response
 */
trait ResponseMessageTrait
{
    /**
     * Display success json response message.
     *
     * @param string $message
     * @param array|object $data
     * @param int $httpCode
     * @return \Illuminate\Http\Response
     */
    public function success($data = null, $message = null, $httpCode = HttpCode::SUCCESS)
    {
        $request = request();

        return response()->json([
            'success' => true,
            'message' => $message ?: __("message.success_{$request->method()}", ['menu' => str_replace('-', ' ', $request->segment(3))]),
            'data'    => $data
        ], $httpCode);
    }

    /**
     * Display failed json response message.
     *
     * @param string $message
     * @param int $httpCode
     * @return \Illuminate\Http\Response
     */
    public function fails($message = null, $httpCode = HttpCode::FAIL)
    {
        $request = request();

        return response()->json([
            'success' => false,
            'message' => $message ?: __("message.fails_{$request->method()}", ['menu' => str_replace('-', ' ', $request->segment(3))]),
            'data'    => null
        ], $httpCode);
    }
}
