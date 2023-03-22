<?php

namespace Luchavez\ApiSdkKit\Traits;

use Luchavez\ApiSdkKit\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasAuditLogsTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait HasAuditLogsTrait
{
    /**
     * @return MorphMany
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'audit_loggable');
    }

    /**
     * @param  AuditLog  $auditLog
     * @return false|Model
     */
    public function attachAuditLog(AuditLog $auditLog): Model|bool
    {
        return $this->auditLogs()->save($auditLog);
    }
}
