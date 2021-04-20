<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
    }

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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['token']]);
    }
}
