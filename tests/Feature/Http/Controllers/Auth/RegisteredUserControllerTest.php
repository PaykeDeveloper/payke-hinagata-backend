<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Auth\Invitation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use DatabaseMigrations;
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

        $email = $invitation->email;
        $password = $this->faker->password(minLength: 8);
        $data = [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
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
        Invitation::factory()->pending()->create([
            'token' => hash('sha256', $token),
        ]);

        $password = $this->faker->password(minLength: 8);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'token' => $token,
        ];

        $response = $this->postJson('register', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }
}
