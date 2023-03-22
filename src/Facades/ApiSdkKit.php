<?php

namespace Luchavez\ApiSdkKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ApiSdkKit
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\ApiSdkKit\Services\ApiSdkKit
 */
class ApiSdkKit extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'api-sdk-kit';
    }
}
