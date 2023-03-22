<?php

namespace Luchavez\ApiSdkKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MakeRequest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\ApiSdkKit\Services\MakeRequest
 */
class MakeRequest extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'make-request';
    }
}
