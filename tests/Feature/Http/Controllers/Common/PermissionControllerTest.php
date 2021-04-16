<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use function App\Models\Common\getPermissionName;

class PermissionControllerTest extends TestCase
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
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->givePermissionTo(getPermissionName(PermissionType::VIEW_ALL, Permission::RESOURCE));
        $response = $this->actingAs($user)->getJson(route('permissions.index'));

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
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('permissions.index'));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
