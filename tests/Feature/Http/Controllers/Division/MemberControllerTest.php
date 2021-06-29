<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Division;

use App\Models\Common\UserRole;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

/**
 * @group division
 */
class MemberControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;
    private Member $target_member;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
        $this->member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $this->target_member = Member::factory()->create([
            'division_id' => $this->division->id,
        ]);
    }

    /**
     * [正常系]
     */

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testIndexSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.members.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(Member::whereDivisionId($this->division->id)->count())
            ->assertJsonFragment($this->target_member->toArray());
    }

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);
        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
            'role_names' => [MemberRole::MEMBER]
        ];

        $response = $this->postJson(route('divisions.members.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testShowSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->target_member->toArray());
    }

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testUpdateSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->putJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testDestroySuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]));

        $response->assertNoContent();

        $result = Member::find($this->target_member->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * @dataProvider provideUnAuthorizedRole
     */
    public function testIndexUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.members.index', ['division' => $this->division->id]));

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedRole
     */
    public function testStoreUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);
        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
            'role_names' => [MemberRole::MEMBER]
        ];

        $response = $this->postJson(route('divisions.members.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideAuthorizedRole
     */
    public function testStoreDuplicated($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = [
            'user_id' => $this->user->id,
            'role_names' => [MemberRole::MEMBER]
        ];

        $response = $this->postJson(route('divisions.members.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['user_id']]);
    }

    /**
     * @dataProvider provideUnAuthorizedRole
     */
    public function testShowUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]));

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedRole
     */
    public function testUpdateUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->putJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedRole
     */
    public function testDestroyUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $this->target_member->id,
        ]));

        $response->assertNotFound();
    }

    public function provideAuthorizedRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, null],
            [UserRole::STAFF, MemberRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedRole(): array
    {
        return [
            [UserRole::ORGANIZER, MemberRole::MEMBER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }
}
