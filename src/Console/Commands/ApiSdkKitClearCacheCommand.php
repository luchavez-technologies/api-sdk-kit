<?php

namespace Luchavez\ApiSdkKit\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ApiSdkKitClearCacheCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ApiSdkKitClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ask:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached Model-AuditLogHandler pairings.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        apiSdkKit()->clearCache();

        return self::SUCCESS;
    }
}
