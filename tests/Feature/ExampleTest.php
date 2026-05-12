<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Your AdminAuth middleware requires admin_id in session.
        $response = $this->withSession(['admin_id' => 1])->get('/');

        $response->assertStatus(200);
    }
}
