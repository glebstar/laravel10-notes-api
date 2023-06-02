<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class NoteTest extends TestCase
{
    /**
     * Create user
     *
     * @return string
     */
    public function test_register(): string
    {
        // registration
        $response = $this->postJson ('api/register', ['name' => 'Tom']);
        $response
            ->assertStatus(401)
            ->assertJson([
                'password' => ['The password field is required.'],
            ]);

        $user = User::factory()->make();

        $response = $this->postJson ('api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertTrue(isset($response['access_token']));

        return $response['access_token'];
    }
}
