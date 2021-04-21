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
class DivisionControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;

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
    }

    /**
     * [正常系]
     */

    /**
     * @dataProvider provideAuthorizedIndexOwnRole
     */
    public function testIndexSuccessOwn($user_role)
    {
        $this->user->syncRoles($user_role);

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->division->toArray());
    }

    /**
     * @dataProvider provideAuthorizedIndexOtherRole
     */
    public function testIndexSuccessOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(Division::count())
            ->assertJsonFragment($division->toArray());
    }

    /**
     * @dataProvider provideAuthorizedCreateRole
     */
    public function testStoreSuccess($user_role)
    {
        $this->user->syncRoles($user_role);

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.store'), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedShowOwnRole
     */
    public function testShowSuccessOwn($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $response = $this->getJson(route('divisions.show', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonFragment($this->division->toArray());
    }

    /**
     * @dataProvider provideAuthorizedShowOtherRole
     */
    public function testShowSuccessOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $response = $this->getJson(route('divisions.show', ['division' => $division->id]));

        $response->assertOk()
            ->assertJsonFragment($division->toArray());
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOwnRole
     */
    public function testUpdateSuccessOwn($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOtherRole
     */
    public function testUpdateSuccessOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOwnRole
     */
    public function testDestroySuccessOwn($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertNoContent();

        $result = Division::find($this->division->id);
        $this->assertNull($result);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOtherRole
     */
    public function testDestroySuccessOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNoContent();

        $result = Division::find($division->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * @dataProvider provideUnAuthorizedIndexRole
     */
    public function testIndexUnAuthorizedOwn($user_role)
    {
        $this->user->syncRoles($user_role);

        $response = $this->getJson(route('divisions.index'));

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedCreateRole
     */
    public function testStoreUnAuthorized($user_role, $status)
    {
        $this->user->syncRoles($user_role);

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.store'), $data);

        $response->assertStatus($status);
    }

    /**
     * @dataProvider provideUnAuthorizedShowRole
     */
    public function testShowUnAuthorized($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $response = $this->getJson(route('divisions.show', ['division' => $this->division->id]));

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOwnRole
     */
    public function testUpdateUnAuthorizedOwn($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOtherRole
     */
    public function testUpdateUnAuthorizedOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertNotFound();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOwnRole
     */
    public function testDestroyUnAuthorizedOwn($user_role, $member_role)
    {
        $this->user->syncRoles($user_role);
        $this->member->syncRoles($member_role);

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOtherRole
     */
    public function testDestroyUnAuthorizedOther($user_role)
    {
        $this->user->syncRoles($user_role);
        $division = Division::factory()->create();

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNotFound();
    }

    public function provideAuthorizedIndexOwnRole(): array
    {
        return [
            [UserRole::STAFF],
        ];
    }

    public function provideAuthorizedIndexOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedIndexRole(): array
    {
        return [
            [UserRole::ORGANIZER],
        ];
    }

    public function provideAuthorizedShowOwnRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, null],
            [UserRole::STAFF, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }

    public function provideAuthorizedShowOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
        ];
    }

    public function provideUnAuthorizedShowRole(): array
    {
        return [
            [UserRole::ORGANIZER, null],
            [UserRole::STAFF, null],
        ];
    }

    public function provideAuthorizedCreateRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedCreateRole(): array
    {
        return [
            [UserRole::ORGANIZER, Response::HTTP_NOT_FOUND],
            [UserRole::STAFF, Response::HTTP_FORBIDDEN],
        ];
    }

    public function provideAuthorizedUpdateDeleteOwnRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, null],
            [UserRole::ORGANIZER, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedUpdateDeleteOwnRole(): array
    {
        return [
            [UserRole::ORGANIZER, MemberRole::MEMBER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }

    public function provideAuthorizedUpdateDeleteOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedUpdateDeleteOtherRole(): array
    {
        return [
            [UserRole::ORGANIZER],
            [UserRole::STAFF],
        ];
    }
}
