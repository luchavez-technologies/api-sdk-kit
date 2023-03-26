<?php

namespace Luchavez\ApiSdkKit\Abstracts;

use Luchavez\ApiSdkKit\Interfaces\CanGetHealthCheckInterface;
use Luchavez\ApiSdkKit\Interfaces\CanGetNewApiKeysInterface;
use Luchavez\ApiSdkKit\Services\SimpleHttp;
use Luchavez\ApiSdkKit\Traits\UsesHttpFieldsTrait;

/**
 * Class BaseApiSdkService
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
abstract class BaseApiSdkService
{
    use UsesHttpFieldsTrait;

    /**
     * @return bool
     */
    public function canGetHealthCheck(): bool
    {
        return $this instanceof CanGetHealthCheckInterface || method_exists($this, 'getHealthCheck');
    }

    /**
     * @return bool
     */
    public function canGetNewApiKeys(): bool
    {
        return $this instanceof CanGetNewApiKeysInterface || method_exists($this, 'getNewApiKeys');
    }

    /***** GETTERS & SETTERS *****/

    /**
     * @return string
     */
    abstract public function getBaseUrl(): string;

    /**
     * @return SimpleHttp
     * @deprecated
     */
    public function getMakeRequest(): SimpleHttp
    {
        return makeRequest($this->getBaseUrl())
            ->httpOptions($this->getHttpOptions())
            ->headers($this->getHeaders());
    }

    /**
     * @param bool $return_as_model
     * @return SimpleHttp
     */
    public function getHttp(bool $return_as_model = true): SimpleHttp
    {
        return simpleHttp($this->getBaseUrl(), $return_as_model)
            ->httpOptions($this->getHttpOptions())
            ->headers($this->getHeaders());
    }
}
