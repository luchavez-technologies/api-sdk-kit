<?php

namespace Luchavez\ApiSdkKit\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CanGetNewApiKeysException
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class CanGetNewApiKeysException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return customResponse()
            ->code(config('api-sdk-kit.get_new_api_keys_exception.code'))
            ->message(config('api-sdk-kit.get_new_api_keys_exception.message'))
            ->generate();
    }
}
