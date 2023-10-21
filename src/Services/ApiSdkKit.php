<?php

namespace Luchavez\ApiSdkKit\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Luchavez\ApiSdkKit\Abstracts\BaseAuditLogHandler;
use Luchavez\ApiSdkKit\Models\AuditLog;
use Luchavez\StarterKit\Traits\HasTaggableCacheTrait;
use RuntimeException;

/**
 * Class ApiSdkKit
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ApiSdkKit
{
    use HasTaggableCacheTrait;

    /**
     * @return string
     */
    public function getMainTag(): string
    {
        return 'ask';
    }

    /**
     * @var array
     */
    protected array $auditLogHandlers = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Grab copy from cache if exists
        $this->auditLogHandlers = $this->getAuditLogHandlers()->toArray();
    }

    /**
     * @param  bool  $rehydrate
     * @return Collection
     */
    public function getAuditLogHandlers(bool $rehydrate = false): Collection
    {
        $key = 'audit_log_handlers';

        return $this->getCache([], $key, fn () => collect($this->auditLogHandlers), $rehydrate);
    }

    /**
     * @param  Model  $model
     * @param  AuditLog  $audit_log
     * @return BaseAuditLogHandler|null
     */
    public function getAuditLogHandler(Model $model, AuditLog $audit_log): ?BaseAuditLogHandler
    {
        if ($handler = $this->getAuditLogHandlers()->get(get_class($model))) {
            return new $handler($model, $audit_log);
        }

        return null;
    }

    /**
     * @param  string  $model_class
     * @param  string  $audit_log_handler_class
     * @param  bool  $override
     * @return void
     */
    public function addAuditLogHandler(string $model_class, string $audit_log_handler_class, bool $override = false): void
    {
        $this->validateClass($model_class, Model::class);
        $this->validateClass($audit_log_handler_class, BaseAuditLogHandler::class);

        // Grab copy of Collection
        $handlers = $this->getAuditLogHandlers();

        // Add to collection if model class not yet exists
        // Add to collection if model class exists but AuditLogHandler is different and $override is false
        // Throw error if model class exists and AuditLogHandler is different and $override is true

        $differentHandler = false;

        if (! $handlers->has($model_class) || ($differentHandler = $handlers->get($model_class) !== $audit_log_handler_class)) {
            if ($differentHandler && ! $override) {
                throw new RuntimeException('AuditLogHandler already exists for specified model: '.$model_class);
            }

            // Add to Collection
            $this->auditLogHandlers = $handlers->put($model_class, $audit_log_handler_class)->toArray();

            // Invoke to generate new cache
            $this->getAuditLogHandlers(true);
        }
    }

    /***** CONFIG RELATED *****/

    /**
     * @param  bool  $rehydrate
     * @return Collection
     */
    public function getHttpMethods(bool $rehydrate = false): Collection
    {
        $key = 'http_methods';

        return $this->getCache([], $key, fn () => collect(config('api-sdk-kit.http_methods')), $rehydrate);
    }

    /**
     * @param  string  $method
     * @param  bool  $rehydrate
     * @return int|null
     */
    public function getCodeByHttpMethod(string $method, bool $rehydrate = false): ?int
    {
        return $this->getHttpMethods($rehydrate)->get($method);
    }

    /**
     * @param  int  $code
     * @param  bool  $rehydrate
     * @return string|null
     */
    public function getHttpMethodByCode(int $code, bool $rehydrate = false): ?string
    {
        return $this->getHttpMethods($rehydrate)->flip()->get($code);
    }

    /**
     * @return bool
     */
    public function shouldForceDeleteUnattachedAuditLog(): bool
    {
        return config('api-sdk-kit.audit_log_force_delete_unattached');
    }
}
