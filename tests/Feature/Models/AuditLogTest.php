<?php

namespace Luchavez\ApiSdkKit\Feature\Models;

use Tests\TestCase;

/**
 * Class AuditLogTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLogTest extends TestCase
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
