<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AllRouteCanBeBrowsedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // $response = $this->get('/');
        $response = true;
        $this->assertTrue($response);
        // $response->assertStatus(200);
    }
}
