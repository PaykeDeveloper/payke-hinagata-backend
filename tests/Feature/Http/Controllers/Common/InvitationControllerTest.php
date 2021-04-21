<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        /** @var User $user */
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testIndexSuccess($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.index'));

        $response->assertOk()
            ->assertJsonCount(Invitation::count())
            ->assertJsonFragment($invitation->toArray());
    }

    /**
     * 作成ができる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreSuccess($role)
    {
        $this->user->syncRoles($role);
        $email = $this->faker->email;
        $data = [
            'name' => $this->faker->name,
            'email' => $email,
            'locale' => 'ja',
            'role_names' => [],
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertOk()
            ->assertJsonFragment(['email' => $email]);
    }

    /**
     * データの取得ができる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testShowSuccess($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.show', ['invitation' => $invitation->id]));

        $response->assertOk()
            ->assertJson($invitation->toArray());
    }

    /**
     * データの更新ができる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testUpdateSuccess($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->pending()->create();
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('invitations.update', ['invitation' => $invitation->id]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * 削除ができる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testDestroySuccess($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->pending()->create();

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertNoContent();

        $result = Invitation::find($invitation->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * 許可されないユーザーで一覧取得。
     *
     * @dataProvider provideUnAuthorizedRole
     */
    public function testIndexUnAuthorized($role)
    {
        $this->user->syncRoles($role);
        Invitation::factory()->create();

        $response = $this->getJson(route('invitations.index'));

        $response->assertNotFound();
    }

    /**
     * データが存在しない場合は空の配列となる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testIndexEmpty($role)
    {
        $this->user->syncRoles($role);
        Invitation::query()->delete();

        $response = $this->getJson(route('invitations.index'));

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * 許可されないユーザーで詳細取得。
     *
     * @dataProvider provideUnAuthorizedRole
     */
    public function testShowUnAuthorized($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.show', ['invitation' => $invitation->id]));

        $response->assertNotFound();
    }

    /**
     * 存在しないIDで取得するとエラーになる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testShowNotFound($role)
    {
        $this->user->syncRoles($role);

        $response = $this->getJson(route('invitations.show', ['invitation' => 11111]));

        $response->assertNotFound();
    }

    /**
     * 許可されないユーザーで作成。
     *
     * @dataProvider provideUnAuthorizedRole
     */
    public function testStoreUnAuthorized($role)
    {
        $this->user->syncRoles($role);
        $email = $this->faker->email;
        $data = [
            'name' => $this->faker->name,
            'email' => $email,
            'locale' => 'ja',
            'role_names' => [],
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertNotFound();
    }

    /**
     * メールアドレスが不正な値で作成するとエラーになる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreInvalidEmail($role)
    {
        $this->user->syncRoles($role);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->name,
            'locale' => 'ja',
            'role_names' => [],
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 存在するユーザーで作成するとエラーになる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreExistsUser($role)
    {
        $this->user->syncRoles($role);
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name,
            'email' => $user->email,
            'locale' => 'ja',
            'role_names' => [],
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 招待済みのメールアドレスで作成するとエラーになる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreExistsInvitation($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->pending()->create();
        $data = [
            'name' => $this->faker->name,
            'email' => $invitation->email,
            'locale' => 'ja',
            'role_names' => [],
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * 許可されないユーザーで更新。
     *
     * @dataProvider provideUnAuthorizedRole
     */
    public function testUpdateUnAuthorized($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->pending()->create();
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(route('invitations.update', ['invitation' => $invitation->id]), $data);

        $response->assertNotFound();
    }

    /**
     * 更新ができない。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testUpdateDenied($role)
    {
        $this->user->syncRoles($role);
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
     * 削除ができる。
     *
     * @dataProvider provideUnAuthorizedRole
     */
    public function testDestroyUnAuthorized($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->pending()->create();

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertNotFound();
    }

    /**
     * ステータスが待ちのデータ以外を削除するとエラーになる。
     *
     * @dataProvider provideAuthorizedRole
     */
    public function testDeleteStatusIsNotPending($role)
    {
        $this->user->syncRoles($role);
        $invitation = Invitation::factory()->create([
            'status' => $this->faker->randomElement([InvitationStatus::DENIED, InvitationStatus::APPROVED]),
        ]);

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function provideAuthorizedRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::ORGANIZER],
        ];
    }

    public function provideUnAuthorizedRole(): array
    {
        return [
            [UserRole::MANAGER],
            [UserRole::STAFF],
        ];
    }
}
