<?php

namespace Tests\Unit;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    public function test_basic()
    {
        $this->assertEquals(4, 2 + 2);
    }
}