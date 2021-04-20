<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use App\Models\Common\PermissionType;
use App\Models\Common\UserRole;
use App\Models\Division\MemberRole;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

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
    public function testIndexSuccessAll()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $user = User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(User::count())
            ->assertJsonFragment($user->toArray());
    }

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccessOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));
        $user = User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonMissing(['id' => $user->id]);
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccessAll()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', ['user' => $user->id]));

        $response->assertOk()
            ->assertJson($user->toArray());
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccessOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));

        $response = $this->getJson(route('users.show', ['user' => $this->user->id]));

        $response->assertOk()
            ->assertJson($this->user->toArray());
    }

    /**
     * データの更新ができる。
     */
    public function testUpdateSuccessAll()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, User::RESOURCE));
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
            'role_names' => UserRole::all(),
        ];

        $response = $this->patchJson(route('users.update', ['user' => $user->id]), $data);

        $response->assertOk()
            ->assertJsonFragment(['name' => $data['name']]);
    }

    /**
     * データの更新ができる。
     */
    public function testUpdateSuccessOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, User::RESOURCE));
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('users.update', ['user' => $this->user->id]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccessALL()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, User::RESOURCE));
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

        $response->assertNoContent();

        $result = Invitation::find($user->id);
        $this->assertNull($result);
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccessOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_OWN, User::RESOURCE));

        $response = $this->deleteJson(route('users.destroy', ['user' => $this->user->id]));

        $response->assertNoContent();

        $result = Invitation::find($this->user->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * 取得で、権限エラーになる。
     */
    public function testIndexUnAuthorized()
    {
        $response = $this->getJson(route('users.index'));

        $response->assertNotFound();
    }

    /**
     * 作成はエラーになる。
     */
    public function testStoreNotFound()
    {
        $response = $this->postJson(route('users.index'), []);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * 不正なロールの設定でエラーになる。
     */
    public function testUpdateInValidRoles()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, User::RESOURCE));
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
            'role_names' => [MemberRole::MANAGER],
        ];

        $response = $this->patchJson(route('users.update', ['user' => $user->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['role_names.0']]);
    }


    /**
     * 更新で、権限エラーになる。
     */
    public function testUpdateUnAuthorizedAll()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, User::RESOURCE));
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('users.update', ['user' => $user->id]), $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * 更新で、権限エラーになる。
     */
    public function testUpdateUnAuthorizedOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('users.update', ['user' => $this->user->id]), $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * 削除で、権限エラーになる。
     */
    public function testDestroyUnAuthorizedALL()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_OWN, User::RESOURCE));
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * 削除で、権限エラーになる。
     */
    public function testDestroyUnAuthorizedOwn()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, User::RESOURCE));

        $response = $this->deleteJson(route('users.destroy', ['user' => $this->user->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
