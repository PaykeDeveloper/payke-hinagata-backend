<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Division;

use App\Models\Common\PermissionType;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group division
 */
class MemberControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
        $this->member = Member::factory()->create([
            'division_id' => $this->division->id,
        ]);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->member->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->member->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Member::RESOURCE));

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->putJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Member::RESOURCE));

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertNoContent();

        $result = Member::find($this->member->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsMember()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.index', [
            'division' => $this->division->id,
        ]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($member->toArray());
    }

    public function testShowSuccessAsMember()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]));

        $response->assertOk()
            ->assertJson($member->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsMember()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Member::RESOURCE));

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->patchJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * [準正常系]
     */

    /**
     * 権限の無い別のメンバーにアクセスするとエラーになる。
     */
    public function testShowNotFoundAsMember()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        $response = $this->getJson(route('divisions.members.index', [
            'division' => $this->division->id,
        ]));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsMember()
    {
        /** @var Member $member */
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->patchJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]), $data);

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsMember()
    {
        /** @var Member $member */
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Member::RESOURCE));

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]));

        $response->assertForbidden();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Member::RESOURCE));

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->patchJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Member::RESOURCE));

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $member->id,
        ]));

        $response->assertNotFound();
    }
}
