<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Auth\Invitation;
use App\Models\Auth\InvitationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use DatabaseMigrations;
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
    public function testIndexSuccess()
    {
        $invitation = Invitation::factory()->create(['user_id' => $this->user->id]);

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
        $invitation = Invitation::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('invitations.show', ['invitation' => $invitation->id]));

        $response->assertOk()
            ->assertJson($invitation->toArray());
    }


    /**
     * 削除ができる。
     */
    public function testDestroySuccess()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => $this->user->id,
            'status' => InvitationStatus::PENDING,
        ]);

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertNoContent();

        $result = Invitation::find($invitation->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * ユーザーに紐づかないデータは取得されない。
     */
    public function testIndexEmpty()
    {
        Invitation::factory()->create();

        $response = $this->getJson(route('invitations.index'));

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * ユーザーに紐づかないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->getJson(route('invitations.show', ['invitation' => $invitation->id]));

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
        $invitation = Invitation::factory()->create([
            'user_id' => $this->user->id,
        ]);
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
        $invitation = Invitation::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'email' => $this->faker->email,
        ];

        $response = $this->patchJson("/api/v1/invitations/{$invitation->id}", $data);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * ユーザーに紐づかないIDで削除するとエラーになる。
     */
    public function testDeleteNotFound()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertNotFound();
    }

    /**
     * ステータスが待ちのデータ以外を削除するとエラーになる。
     */
    public function testDeleteStatusIsNotPending()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => $this->user->id,
            'status' => $this->faker->randomElement([InvitationStatus::DENIED, InvitationStatus::APPROVED]),
        ]);

        $response = $this->deleteJson(route('invitations.destroy', ['invitation' => $invitation->id]));

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
