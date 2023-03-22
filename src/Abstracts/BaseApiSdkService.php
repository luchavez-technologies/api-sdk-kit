<?php

namespace Luchavez\ApiSdkKit\Abstracts;

use Luchavez\ApiSdkKit\Interfaces\CanGetHealthCheckInterface;
use Luchavez\ApiSdkKit\Interfaces\CanGetNewApiKeysInterface;
use Luchavez\ApiSdkKit\Services\MakeRequest;
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
     * @return MakeRequest
     */
    public function getMakeRequest(): MakeRequest
    {
        return makeRequest($this->getBaseUrl())
            ->httpOptions($this->getHttpOptions())
            ->headers($this->getHeaders());
    }
}
