<?php

namespace Luchavez\ApiSdkKit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Luchavez\ApiSdkKit\Traits\HasAuditLogFactoryTrait;
use Luchavez\StarterKit\Casts\AsCompressedArrayCast;
use Luchavez\StarterKit\Casts\AsCompressedCollectionCast;
use Luchavez\StarterKit\Interfaces\HasHttpStatusCodeInterface;
use Luchavez\StarterKit\Traits\ModelOwnedTrait;
use Luchavez\StarterKit\Traits\UsesUUIDTrait;

/**
 * Class AuditLog
 *
 * @method static Builder attached(bool $bool = true) Get logs that has or lacks attachment
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLog extends Model implements HasHttpStatusCodeInterface
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use HasAuditLogFactoryTrait;
    use ModelOwnedTrait {
        ModelOwnedTrait::owner as user;
    }

    // For ModelOwnedTrait
    const OWNER_ID = 'user_id';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @var string[]
     */
    protected $casts = [
        'headers' => AsCompressedArrayCast::class,
        'data' => AsCompressedCollectionCast::class,
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'parse_url',
    ];

    /***** RELATIONSHIPS *****/

    /**
     * @return MorphTo
     */
    public function auditLoggable(): MorphTo
    {
        return $this->morphTo();
    }

    /***** ACCESSORS & MUTATORS *****/

    /**
     * @return array|null
     */
    public function getParseUrlAttribute(): ?array
    {
        return parse_url($this->url);
    }

    /**
     * @param  string  $method
     * @return void
     */
    public function setMethodAttribute(string $method): void
    {
        $this->attributes['method'] = apiSdkKit()->getCodeByHttpMethod($method);
    }

    /**
     * @param  int  $code
     * @return string|null
     */
    public function getMethodAttribute(int $code): ?string
    {
        return apiSdkKit()->getHttpMethodByCode($code);
    }

    /***** SCOPES *****/

    public function scopeAttached(Builder $builder, bool $bool = true)
    {
        $columns = ['audit_loggable_type', 'audit_loggable_id'];

        return $builder->when(
            $bool,
            fn (Builder $builder) => $builder->whereNotNull($columns),
            fn (Builder $builder) => $builder->whereNull($columns)
        );
    }

    /*****  OTHER METHODS *****/

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Determine if the request was successful.
     *
     * @return bool
     */
    public function successful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * Determine if the request was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful();
    }

    /**
     * Determine if the response code was "OK".
     *
     * @return bool
     */
    public function ok(): bool
    {
        return $this->status === 200;
    }

    /**
     * Determine if the response code was "OK".
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->ok();
    }

    /**
     * Determine if the response was a 401 "Unauthorized" response.
     *
     * @return bool
     */
    public function unauthorized(): bool
    {
        return $this->status === 401;
    }

    /**
     * Determine if the response was a 403 "Forbidden" response.
     *
     * @return bool
     */
    public function forbidden(): bool
    {
        return $this->status === 403;
    }

    /**
     * Determine if the response was a 403 "Forbidden" response.
     *
     * @return bool
     */
    public function isForbidden(): bool
    {
        return $this->forbidden();
    }

    /**
     * Determine if the response indicates a client or server error occurred.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->serverError() || $this->clientError();
    }

    /**
     * Determine if the response indicates a client error occurred.
     *
     * @return bool
     */
    public function clientError(): bool
    {
        return $this->status >= 400 && $this->status < 500;
    }

    /**
     * Determine if the response indicates a client error occurred.
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->clientError();
    }

    /**
     * Determine if the response indicates a server error occurred.
     *
     * @return bool
     */
    public function serverError(): bool
    {
        return $this->status >= 500;
    }

    /**
     * Determine if the response indicates a server error occurred.
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->serverError();
    }
}
