<?php

namespace Luchavez\ApiSdkKit\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Luchavez\StarterKit\Abstracts\BaseJsonSerializable;

/**
 * Interface CanHealthCheckInterface
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
interface CanGetHealthCheckInterface
{
    /**
     * @param  mixed|null  $data
     * @return Model|BaseJsonSerializable|PromiseInterface|Response|Collection|array
     */
    public function getHealthCheck(mixed $data = null): Model|BaseJsonSerializable|PromiseInterface|Response|Collection|array;
}
