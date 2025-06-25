<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $commercial = User::factory()->create(["role" => "commercial"]);
        $client = User::factory()->create(["role" => "client"]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($commercial->isAdmin());
        
        $this->assertTrue($commercial->isCommercial());
        $this->assertFalse($client->isCommercial());
        
        $this->assertTrue($client->isClient());
        $this->assertFalse($admin->isClient());
    }

    public function test_user_creation()
    {
        $user = User::factory()->create([
            "name" => "Test Commercial",
            "email" => "test@btp.com",
            "role" => "commercial"
        ]);

        $this->assertDatabaseHas("users", [
            "name" => "Test Commercial",
            "email" => "test@btp.com",
            "role" => "commercial"
        ]);
    }
}