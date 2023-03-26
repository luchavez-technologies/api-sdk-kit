<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

use Luchavez\ApiSdkKit\Services\SimpleHttp;

/***** SIMPLE HTTP SERVICE *****/

if (! function_exists('makeRequest')) {
    /**
     * @param  string|null  $base_url
     * @param  bool  $return_as_model
     * @return SimpleHttp
     * @deprecated
     */
    function makeRequest(?string $base_url, bool $return_as_model = true): SimpleHttp
    {
        return resolve('simple-http', get_defined_vars());
    }
}

if (! function_exists('make_request')) {
    /**
     * @param  string|null  $base_url
     * @param  bool  $return_as_model
     * @return SimpleHttp
     * @deprecated
     */
    function make_request(?string $base_url, bool $return_as_model = true): SimpleHttp
    {
        return makeRequest($base_url, $return_as_model);
    }
}

if (! function_exists('simpleHttp')) {
    /**
     * @param  string|null  $base_url
     * @param  bool  $return_as_model
     * @return SimpleHttp
     */
    function simpleHttp(?string $base_url, bool $return_as_model = true): SimpleHttp
    {
        return resolve('simple-http', get_defined_vars());
    }
}

if (! function_exists('simple_http')) {
    /**
     * @param string|null $base_url
     * @param bool $return_as_model
     * @return SimpleHttp
     */
    function simple_http(?string $base_url, bool $return_as_model = true): SimpleHttp
    {
        return simpleHttp($base_url, $return_as_model);
    }
}

/***** OTHER FUNCTIONS *****/

if (! function_exists('isInternalUrl')) {
    /**
     * @param  string  $url
     * @return bool
     */
    function isInternalUrl(string $url): bool
    {
        $arr = parse_url($url);

        return isset($arr['host']) && isset($_SERVER['HTTP_HOST']) && strcmp($arr['host'], $_SERVER['HTTP_HOST']) === 0;
    }
}

if (! function_exists('is_internal_url')) {
    /**
     * @param  string  $url
     * @return bool
     */
    function is_internal_url(string $url): bool
    {
        return isInternalUrl($url);
    }
}
