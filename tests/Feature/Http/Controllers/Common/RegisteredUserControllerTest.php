<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    /**
     * [正常系]
     */

    /**
     * サインアップできる。
     */
    public function testStoreSuccess()
    {
        $token = Str::random(60);
        $invitation = Invitation::factory()->pending()->create([
            'token' => hash('sha256', $token),
        ]);

        $password = $this->faker->password(minLength: 8);
        $data = [
            'password' => $password,
            'password_confirmation' => $password,
            'id' => $invitation->id,
            'token' => $token,
        ];

        $response = $this->postJson('register', $data);

        $response->assertCreated();
    }

    /**
     * [準正常系]
     */

    /**
     * 招待されたメール以外はエラーになる。
     */
    public function testStoreWrongEmail()
    {
        $token = Str::random(60);
        $invitation = Invitation::factory()->pending()->create([
            'token' => hash('sha256', $token),
        ]);

        $password = $this->faker->password(minLength: 8);
        $data = [
            'password' => $password,
            'password_confirmation' => $password,
            'id' => $invitation->id,
            'token' => "{$token}test",
        ];

        $response = $this->postJson('register', $data);

        $response->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['token']]);
    }
}
