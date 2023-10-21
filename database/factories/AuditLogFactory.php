<?php

namespace Database\Factories;

// Model
use Illuminate\Database\Eloquent\Factories\Factory;
use Luchavez\ApiSdkKit\Models\AuditLog;

/**
 * Class AuditLog
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
