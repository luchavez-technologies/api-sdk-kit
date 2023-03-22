<?php

namespace Luchavez\ApiSdkKit\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class DeleteOrphanAuditLogsCommandTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeleteOrphanAuditLogsCommandTest extends TestCase
{
    /**
     * Example Test
     *
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
