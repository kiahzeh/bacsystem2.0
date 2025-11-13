<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable CSRF verification during tests to avoid 419 responses
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        
        // Disable Vite during tests to avoid asset loading errors
        $this->withoutVite();
        
        // Run test database seeder to create default department
        $this->seed(\Database\Seeders\TestDatabaseSeeder::class);
    }
}
