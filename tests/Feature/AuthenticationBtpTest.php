<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationBtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_commercial()
    {
        $commercial = User::factory()->create([
            "email" => "commercial@btp.com",
            "password" => Hash::make("password123"),
            "role" => "commercial",
            "active" => true
        ]);

        $response = $this->post("/login", [
            "email" => "commercial@btp.com",
            "password" => "password123"
        ]);

        $this->assertAuthenticatedAs($commercial);
    }

    public function test_login_client()
    {
        $client = User::factory()->create([
            "email" => "client@exemple.com",
            "password" => Hash::make("password123"),
            "role" => "client",
            "active" => true
        ]);

        $response = $this->post("/login", [
            "email" => "client@exemple.com", 
            "password" => "password123"
        ]);

        $this->assertAuthenticatedAs($client);
    }

    public function test_login_avec_mauvais_mot_de_passe()
    {
        $user = User::factory()->create([
            "email" => "test@test.com",
            "password" => Hash::make("password123")
        ]);

        $response = $this->post("/login", [
            "email" => "test@test.com",
            "password" => "mauvais_password"
        ]);

        $this->assertGuest();
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post("/logout");
        
        $this->assertGuest();
    }

    public function test_acces_dashboard_selon_role()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $commercial = User::factory()->create(["role" => "commercial"]);
        $client = User::factory()->create(["role" => "client"]);

        // Test qu'on peut accéder au dashboard une fois connecté
        $response = $this->actingAs($admin)->get("/dashboard");
        $response->assertOk();

        $response = $this->actingAs($commercial)->get("/dashboard");
        $response->assertOk();

        $response = $this->actingAs($client)->get("/dashboard");
        $response->assertOk();
    }
}