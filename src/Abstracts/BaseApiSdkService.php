<?php

namespace Luchavez\ApiSdkKit\Abstracts;

use Luchavez\ApiSdkKit\Services\SimpleHttp;
use Luchavez\ApiSdkKit\Traits\SendsHttpRequestTrait;

/**
 * Class BaseApiSdkService
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
abstract class BaseApiSdkService
{
    use SendsHttpRequestTrait;

    /**
     * @return SimpleHttp
     *
     * @deprecated
     */
    public function getMakeRequest(): SimpleHttp
    {
        return makeRequest($this->getBaseUrl())
            ->httpOptions($this->getHttpOptions())
            ->headers($this->getHeaders());
    }
}
