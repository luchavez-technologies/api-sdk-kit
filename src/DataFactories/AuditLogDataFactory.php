<?php

namespace Luchavez\ApiSdkKit\DataFactories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Luchavez\ApiSdkKit\Models\AuditLog;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;

/**
 * Class AuditLogDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @since 2022-05-04
 */
class AuditLogDataFactory extends BaseDataFactory
{
    /**
     * @var string
     */
    public string $user_id;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $method;

    /**
     * @var array
     */
    public array $headers;

    /**
     * @var Collection
     */
    public Collection $data;

    /**
     * @var int
     */
    public int $status;

    /**
     * @var string
     */
    public string $deleted_at;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return AuditLog::query();
    }
}
