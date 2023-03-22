<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

use Luchavez\ApiSdkKit\Services\ApiSdkKit;

if (! function_exists('apiSdkKit')) {
    /**
     * @return ApiSdkKit
     */
    function apiSdkKit(): ApiSdkKit
    {
        return resolve('api-sdk-kit');
    }
}

if (! function_exists('api_sdk_kit')) {
    /**
     * @return ApiSdkKit
     */
    function api_sdk_kit(): ApiSdkKit
    {
        return apiSdkKit();
    }
}
