<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_assertion(): void
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    public function test_database_is_empty(): void
    {
        $this->assertDatabaseCount('users', 0);
    }
}