<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Auth\Invitation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
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
        $password = $this->faker->password;
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

}
