<?php

namespace Luchavez\ApiSdkKit\Observers;

use Luchavez\ApiSdkKit\Models\AuditLog;

/**
 * Class AuditLogObserver
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLogObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the AuditLog "updated" event.
     *
     * @param  AuditLog  $auditLog
     * @return void
     */
    public function updated(AuditLog $auditLog): void
    {
        if (($model = $auditLog->auditLoggable) && $handler = apiSdkKit()->getAuditLogHandler($model, $auditLog)) {
            $handler->handle();
        }
    }
}
