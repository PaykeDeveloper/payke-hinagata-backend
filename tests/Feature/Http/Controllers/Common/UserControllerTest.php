<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($invitation->toArray());
    }

    /**
     * 作成ができる。
     */
    public function testStoreSuccess()
    {
        $email = $this->faker->email;
        $data = [
            'name' => $this->faker->name,
            'email' => $email,
            'locale' => 'ja',
        ];

        $response = $this->postJson(route('invitations.store'), $data);

        $response->assertOk()
            ->assertJsonFragment(['email' => $email]);
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccess()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.show', ['invitation' => $invitation->id]));

        $response->assertOk()
            ->assertJson($invitation->toArray());
    }

    /**
     * データの更新ができる。
     */
    public function testUpdateSuccess()
    {
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
     */
    public function testDestroySuccess()
    {
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
