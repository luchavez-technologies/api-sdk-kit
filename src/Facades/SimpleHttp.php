<?php

namespace Luchavez\ApiSdkKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SimpleHttp
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\ApiSdkKit\Services\SimpleHttp
 */
class SimpleHttp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'simple-http';
    }
}
