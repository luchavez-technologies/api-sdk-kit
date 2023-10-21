<?php

namespace Luchavez\ApiSdkKit\Abstracts;

use Luchavez\ApiSdkKit\Services\SimpleHttp;
use Luchavez\ApiSdkKit\Traits\SendsHttpRequestTrait;

/**
 * Class BaseApiSdkContainer
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @deprecated
 */
abstract class BaseApiSdkContainer
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
