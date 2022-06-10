<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    use RefreshSeedDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Permission::RESOURCE));
        $response = $this->getJson(route('permissions.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name'],
            ]);
    }

    /**
     * [準正常系]
     */

    /**
     * 権限が無い場合は、エラー
     */
    public function testIndexUnAuthorized()
    {
        $response = $this->getJson(route('permissions.index'));

        $response->assertForbidden();
    }
}
