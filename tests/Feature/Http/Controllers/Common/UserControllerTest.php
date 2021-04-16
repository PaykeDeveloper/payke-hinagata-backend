<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\PermissionType;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
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
            ->assertJsonCount(2)
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
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, User::RESOURCE));
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('users.update', ['user' => $user->id]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccessALL()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, User::RESOURCE));
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
     * データが存在しない場合は空の配列となる。
     */
    public function testIndexEmpty()
    {
        $response = $this->getJson(route('invitations.index'));

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * 存在しないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $response = $this->getJson(route('invitations.show', ['invitation' => 11111]));

        $response->assertNotFound();
    }

    /**
     * メールアドレスが不正な値で作成するとエラーになる。
     */
    public function testStoreInvalidEmail()
    {
        $data = [
            'email' => 'not email address',
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 存在するユーザーで作成するとエラーになる。
     */
    public function testStoreExistsUser()
    {
        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 招待済みのメールアドレスで作成するとエラーになる。
     */
    public function testStoreExistsInvitation()
    {
        $invitation = Invitation::factory()->pending()->create();
        $data = [
            'email' => $invitation->email,
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 更新ができない。
     */
    public function testUpdateDenied()
    {
        $invitation = Invitation::factory()->create([
            'status' => $this->faker->randomElement([InvitationStatus::DENIED, InvitationStatus::APPROVED]),
        ]);

        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('invitations.update', ['invitation' => $invitation->id]), $data);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * ステータスが待ちのデータ以外を削除するとエラーになる。
     */
    public function testDeleteStatusIsNotPending()
    {
        $invitation = Invitation::factory()->create([
            'status' => $this->faker->randomElement([InvitationStatus::DENIED, InvitationStatus::APPROVED]),
        ]);

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
