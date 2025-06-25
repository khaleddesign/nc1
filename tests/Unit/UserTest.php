<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'commercial'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'commercial'
        ]);
    }

    public function test_user_role_methods(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commercial = User::factory()->create(['role' => 'commercial']);
        $client = User::factory()->create(['role' => 'client']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($commercial->isAdmin());
        
        $this->assertTrue($commercial->isCommercial());
        $this->assertFalse($client->isCommercial());
        
        $this->assertTrue($client->isClient());
        $this->assertFalse($admin->isClient());
    }
}