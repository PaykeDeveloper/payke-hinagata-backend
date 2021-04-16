<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MyUserControllerTest extends TestCase
{
    use DatabaseMigrations;

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
                'all_permissions' => [],
                'roles' => [],
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

        $response->assertOk()
            ->assertJsonStructure([
                'all_permissions' => [
                    '*' => ['id', 'name'],
                ],
                'roles' => [
                    '*' => ['id', 'name', 'type'],
                ],
            ]);
    }
}
