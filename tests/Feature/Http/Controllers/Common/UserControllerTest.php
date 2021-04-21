<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
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
     *
     * @dataProvider provideAuthorizedViewRole
     */
    public function testIndexSuccess($role)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(User::count())
            ->assertJsonFragment($user->toArray());
    }

    /**
     * データの取得ができる。
     *
     * @dataProvider provideAuthorizedViewRole
     */
    public function testShowSuccess($role)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', ['user' => $user->id]));

        $response->assertOk()
            ->assertJson($user->toArray());
    }

    /**
     * データの更新ができる。
     *
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateSuccess($role)
    {
        $this->user->syncRoles($role);
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
     * 削除ができる。
     *
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testDestroySuccessALL($role)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

        $response->assertNoContent();

        $result = Invitation::find($user->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * 取得で、権限エラーになる。
     *
     * @dataProvider provideUnAuthorizedViewRole
     */
    public function testIndexUnAuthorized($role)
    {
        $this->user->syncRoles($role);

        $response = $this->getJson(route('users.index'));

        $response->assertNotFound();
    }

    /**
     * 作成はエラーになる。
     *
     * @dataProvider provideAuthorizedViewRole
     */
    public function testStoreNotFound($role)
    {
        $this->user->syncRoles($role);

        $response = $this->postJson(route('users.index'), []);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * 不正なロールの設定でエラーになる。
     *
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateInValidRoles($role)
    {
        $this->user->syncRoles($role);
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
     *
     * @dataProvider provideUnAuthorizedOtherRole
     */
    public function testUpdateUnAuthorized($role, $status)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('users.update', ['user' => $user->id]), $data);

        $response->assertStatus($status);
    }

    /**
     * 削除で、権限エラーになる。
     *
     * @dataProvider provideUnAuthorizedOtherRole
     */
    public function testDestroyUnAuthorized($role, $status)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

        $response->assertStatus($status);
    }

    public function provideAuthorizedViewRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::ORGANIZER],
            [UserRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedViewRole(): array
    {
        return [
            [UserRole::STAFF],
        ];
    }

    public function provideAuthorizedOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::ORGANIZER],
        ];
    }

    public function provideUnAuthorizedOtherRole(): array
    {
        return [
            [UserRole::MANAGER, Response::HTTP_FORBIDDEN],
            [UserRole::STAFF, Response::HTTP_NOT_FOUND],
        ];
    }
}
