<?php

namespace Luchavez\ApiSdkKit\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class ApiSdkKitClearCacheCommandTest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ApiSdkKitClearCacheCommandTest extends TestCase
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
