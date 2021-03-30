<?php

namespace Tests\Feature\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * [正常系]
     */

    /**
     * ログインできる。
     */
    public function testStoreTokenSuccess()
    {
        $email = 'foobar@example.com';
        $password = 'foobarPassword';
        $action = new CreateNewUser();
        $action->create([
            'name' => 'foobar',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $data = [
            'email' => $email,
            'password' => $password,
            'package_name' => 'web',
            'platform_type' => 'web',
        ];
        $response = $this->postJson('api/v1/login', $data);

        $response->assertOk()
            ->assertJsonStructure(['token']);
    }

    /**
     * ログアウトできる。
     */
    public function testDestroyTokenSuccess()
    {
        $user = User::factory()->create();
        $token = $user->createToken('exampleToken')->plainTextToken;
        $response = $this->postJson('api/v1/logout', [], [
            'Authorization' => "Bearer $token",
        ]);
        $response->assertNoContent();
    }
}
