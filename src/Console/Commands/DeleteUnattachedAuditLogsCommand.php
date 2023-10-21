<?php

namespace Luchavez\ApiSdkKit\Console\Commands;

use Illuminate\Console\Command;
use Luchavez\ApiSdkKit\Models\AuditLog;

/**
 * Class DeleteUnattachedAuditLogsCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @since 2022-05-04
 */
class DeleteUnattachedAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ask:delete-unattached-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unattached audit logs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $unattachedLogs = AuditLog::attached(false)->where('created_at', '<=', now()->subHour());

        apiSdkKit()->shouldForceDeleteUnattachedAuditLog() ? $unattachedLogs->forceDelete() : $unattachedLogs->delete();

        return self::SUCCESS;
    }
}
