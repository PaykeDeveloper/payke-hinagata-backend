<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyUserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    /**
     * データの取得ができる。
     */
    public function testIndexSuccess()
    {
        $response = $this->getJson('api/v1/user');

        $response->assertOk()
            ->assertJsonFragment([
                'permission_names' => [],
                'role_names' => [],
            ]);
    }

    /**
     * ロールがある場合は、その内容を返す
     */
    public function testIndexHasRole()
    {
        $this->user->assignRole(UserRole::ADMIN);

        $response = $this->getJson('api/v1/user');
        $response->json();

        $response->assertOk();
        $json = $response->json();
        $this->assertNotEmpty($json['permission_names']);
        $this->assertNotEmpty($json['role_names']);
    }

    public function testStoreSuccess()
    {
        $data = [
            'locale' => 'zh_CN',
        ];

        $response = $this->patchJson('api/v1/user', $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }
}
