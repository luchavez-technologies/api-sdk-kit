<?php

namespace Luchavez\ApiSdkKit\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Luchavez\ApiSdkKit\Console\Commands\ApiSdkKitClearCacheCommand;
use Luchavez\ApiSdkKit\Console\Commands\DeleteUnattachedAuditLogsCommand;
use Luchavez\ApiSdkKit\Models\AuditLog;
use Luchavez\ApiSdkKit\Observers\AuditLogObserver;
use Luchavez\ApiSdkKit\Repositories\AuditLogRepository;
use Luchavez\ApiSdkKit\Services\ApiSdkKit;
use Luchavez\ApiSdkKit\Services\SimpleHttp;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider as ServiceProvider;
use Luchavez\StarterKit\Interfaces\ProviderConsoleKernelInterface;

/**
 * Class ApiSdkKitServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @since  2022-01-20
 */
class ApiSdkKitServiceProvider extends ServiceProvider implements ProviderConsoleKernelInterface
{
    /**
     * @var array|string[]
     */
    protected array $commands = [
        DeleteUnattachedAuditLogsCommand::class,
        ApiSdkKitClearCacheCommand::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $morph_map = [
        'audit_log' => AuditLog::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $observer_map = [
        AuditLogObserver::class => AuditLog::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $repository_map = [
        AuditLogRepository::class => AuditLog::class,
    ];

    /**
     * Publishable Environment Variables
     *
     * @example [ 'HELLO_WORLD' => true ]
     *
     * @var array
     */
    protected array $env_vars = [
        'ASK_AUDIT_LOG_FORCE_DELETE_UNATTACHED' => false,
        'ASK_USER_AGENT' => '${APP_NAME}',
    ];

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('api-sdk-kit', fn () => new ApiSdkKit());

        // Register the SimpleHttp Service Container
        $this->app->bind('simple-http', fn ($app, $params) => new SimpleHttp(...$params));

        parent::register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['api-sdk-kit', 'simple-http'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes(
            [
                __DIR__.'/../config/api-sdk-kit.php' => config_path('api-sdk-kit.php'),
            ],
            'api-sdk-kit.config'
        );

        // Registering package commands.
        $this->commands($this->commands);
    }

    /**
     * @param  Schedule  $schedule
     * @return void
     */
    public function registerToConsoleKernel(Schedule $schedule): void
    {
        $schedule->command('ask:delete-unattached-logs')
            ->daily()
            ->runInBackground()
            ->onOneServer();
    }

    public function areHelpersEnabled(): bool
    {
        return false;
    }
}
