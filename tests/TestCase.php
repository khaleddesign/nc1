<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function actingAsAdmin(): static
    {
        $admin = User::factory()->create(['role' => 'admin']);
        return $this->actingAs($admin);
    }

    protected function actingAsCommercial(): static
    {
        $commercial = User::factory()->create(['role' => 'commercial']);
        return $this->actingAs($commercial);
    }

    protected function actingAsClient(): static
    {
        $client = User::factory()->create(['role' => 'client']);
        return $this->actingAs($client);
    }
}