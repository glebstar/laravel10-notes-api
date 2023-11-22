<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Note;
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

    /**
     * Added new note.
     *
     * @param string $token
     *
     * @depends test_register
     *
     * @return array
     */
    public function test_add_note(string $token): array
    {
        $response = $this->postJson(route('note.store'));
        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);

        $response = $this->postJson(route('note.store') . '?token=' . $token);
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The note field is required.',
                'errors' => [
                    'note' => ['The note field is required.'],
                ],
            ]);

        $note = Note::factory()->make();
        $response = $this->postJson(route('note.store') . '?token=' . $token, [
            'note' => $note->text,
        ]);

        $response->assertOk()
            ->assertJson([
                'text' => $note->text,
            ]);

        return [
            'token' => $token,
            'id' => $response['id'],
        ];
    }

    /**
     * Delete a note
     *
     * @param array $params
     *
     * @return void
     * @depends test_add_note
     *
     */
    public function testDeletedNote(array $params): void
    {
        $response = $this->delete(route('note.destroy', $params['id']) . '?token=' . $params['token']);
        $response
            ->assertOk()
            ->assertJson([
                'deleted' => $params['id']
            ]);
    }
}
