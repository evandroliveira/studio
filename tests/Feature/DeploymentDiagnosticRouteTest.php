<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeploymentDiagnosticRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_deployment_diagnostic_route_returns_runtime_snapshot(): void
    {
        $response = $this->get('/diagnostico-hospedagem');

        $response->assertOk();
        $response->assertJsonStructure([
            'build',
            'app' => ['env', 'debug', 'url', 'php', 'laravel'],
            'paths' => ['register', 'agendamento', 'storage_logs', 'storage_sessions'],
            'runtime' => ['session_driver', 'cache_store', 'queue_connection'],
            'database' => ['connected', 'query_test'],
            'logging' => ['channel', 'laravel_log_exists', 'storage_logs_writable'],
            'schema',
            'schema_check_error',
            'missing_items',
        ]);
    }
}