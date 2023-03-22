<?php

namespace Luchavez\ApiSdkKit\Abstracts;

use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider as ServiceProvider;

/**
 * Class BaseApiSdkServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
abstract class BaseApiSdkServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();
    }
}
