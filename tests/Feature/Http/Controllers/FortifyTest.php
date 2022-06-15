<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class FortifyTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    public function testRegister()
    {
        $password = $this->faker->password(minLength: 8);
        $token = Str::random(60);
        /** @var Invitation $invitation */
        $invitation = Invitation::factory()->create([
            'token' => hash('sha256', $token),
            'status' => InvitationStatus::Pending,
        ]);
        $data = [
            'id' => $invitation->id,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $response = $this->postJson('register', $data);
        $user = User::whereEmail($invitation->email)->first();

        $response->assertCreated();
        $this->assertNotNull($user);
    }

    public function testRegisterInvalidToken()
    {
        $password = $this->faker->password(minLength: 8);
        /** @var Invitation $invitation */
        $invitation = Invitation::factory()->create([
            'status' => InvitationStatus::Pending,
        ]);
        $data = [
            'id' => $invitation->id,
            'token' => Str::random(60),
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $response = $this->postJson('register', $data);

        $response->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['token']]);
    }

    public function testLogin()
    {
        $password = $this->faker->password(minLength: 8);
        /** @var User $user */
        $user = User::factory()->create(['password' => Hash::make($password)]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];
        $response = $this->postJson('login', $data);

        $response->assertOk();
    }

    public function testLoginInvalidPassword()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => $this->faker->password(minLength: 8),
        ];
        $response = $this->postJson('login', $data);

        $response->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['email']]);
    }

    public function testLogout()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('logout');

        $response->assertNoContent();
        $this->assertFalse($this->isAuthenticated());
    }

    public function testForgetPassword()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
        ];
        $response = $this->postJson('forgot-password', $data);
        $response->assertOk();
    }

    public function testResetPassword()
    {
        /** @var User $user */
        $user = User::factory()->create();
        Password::broker(config('fortify.passwords'))->sendResetLink([
            'email' => $user->email,
        ], function (User $user, string $token) {
            $password = $this->faker->password(minLength: 8);
            $data = [
                'token' => $token,
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ];
            $response = $this->postJson('reset-password', $data);
            $response->assertOk();
        });
    }

    public function testUpdatePassword()
    {
        $currentPassword = $this->faker->password(minLength: 8);
        /** @var User $user */
        $user = User::factory()->create(['password' => Hash::make($currentPassword)]);
        $this->actingAs($user);

        $password = $this->faker->password(minLength: 8);
        $data = [
            'current_password' => $currentPassword,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $response = $this->putJson('user/password', $data);
        $response->assertOk();
    }
}
