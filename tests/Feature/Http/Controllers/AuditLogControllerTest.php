<?php

namespace Luchavez\ApiSdkKit\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * Class AuditLogControllerTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLogControllerTest extends TestCase
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
