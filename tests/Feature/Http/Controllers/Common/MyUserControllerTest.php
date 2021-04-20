<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * [正常系]
     */

    /**
     * データの取得ができる。
     */
    public function testShowSuccess()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/v1/user');

        $response->assertOk()
            ->assertJsonFragment([
                'permission_names' => [],
                'role_names' => [],
            ]);
    }

    /**
     * ロールがある場合は、その内容を返す
     */
    public function testShowHasRole()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole(UserRole::ADMIN);

        $response = $this->actingAs($user)->getJson('api/v1/user');
        $response->json();

        $response->assertOk();
        $json = $response->json();
        $this->assertNotEmpty($json['permission_names']);
        $this->assertNotEmpty($json['role_names']);
    }
}
