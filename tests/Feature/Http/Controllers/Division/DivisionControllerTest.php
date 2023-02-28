<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Division;

use App\Models\Common\UserRole;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->member = Member::factory()->for($this->user)->for($this->division)->create();
    }

    /**
     * [正常系]
     */

    /**
     * @dataProvider provideAuthorizedIndexOwnRole
     */
    public function testIndexSuccessOwn($userRole)
    {
        $this->user->syncRoles($userRole);

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => $this->division->name]);
    }

    /**
     * @dataProvider provideAuthorizedIndexOtherRole
     */
    public function testIndexSuccessOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(Division::query()->count())
            ->assertJsonFragment(['name' => $division->name]);
    }

    /**
     * @dataProvider provideAuthorizedCreateRole
     */
    public function testStoreSuccess($userRole)
    {
        $this->user->syncRoles($userRole);

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.store'), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedShowOwnRole
     */
    public function testShowSuccessOwn($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->getJson(route('divisions.show', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonFragment(['name' => $this->division->name]);
    }

    /**
     * @dataProvider provideAuthorizedShowOtherRole
     */
    public function testShowSuccessOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $response = $this->getJson(route('divisions.show', ['division' => $division->id]));

        $response->assertOk()
            ->assertJsonFragment(['name' => $division->name]);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOwnRole
     */
    public function testUpdateSuccessOwn($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOtherRole
     */
    public function testUpdateSuccessOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOwnRole
     */
    public function testDestroySuccessOwn($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertNoContent();

        $result = Division::query()->find($this->division->id);
        $this->assertNull($result);
    }

    /**
     * @dataProvider provideAuthorizedUpdateDeleteOtherRole
     */
    public function testDestroySuccessOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNoContent();

        $result = Division::query()->find($division->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * @dataProvider provideUnAuthorizedIndexRole
     */
    public function testIndexUnAuthorizedOwn($userRole)
    {
        $this->user->syncRoles($userRole);

        $response = $this->getJson(route('divisions.index'));

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedCreateRole
     */
    public function testStoreUnAuthorized($userRole)
    {
        $this->user->syncRoles($userRole);

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedShowRole
     */
    public function testShowUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->getJson(route('divisions.show', ['division' => $this->division->id]));

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOwnRole
     */
    public function testUpdateUnAuthorizedOwn($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOtherRole
     */
    public function testUpdateUnAuthorizedOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOwnRole
     */
    public function testDestroyUnAuthorizedOwn($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedUpdateDeleteOtherRole
     */
    public function testDestroyUnAuthorizedOther($userRole)
    {
        $this->user->syncRoles($userRole);
        $division = Division::factory()->create();

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertForbidden();
    }

    public static function provideAuthorizedIndexOwnRole(): array
    {
        return [
            [UserRole::STAFF],
        ];
    }

    public static function provideAuthorizedIndexOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public static function provideUnAuthorizedIndexRole(): array
    {
        return [
            [UserRole::ORGANIZER],
        ];
    }

    public static function provideAuthorizedShowOwnRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, null],
            [UserRole::STAFF, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }

    public static function provideAuthorizedShowOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
        ];
    }

    public static function provideUnAuthorizedShowRole(): array
    {
        return [
            [UserRole::ORGANIZER, null],
            [UserRole::STAFF, null],
        ];
    }

    public static function provideAuthorizedCreateRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public static function provideUnAuthorizedCreateRole(): array
    {
        return [
            [UserRole::ORGANIZER],
            [UserRole::STAFF],
        ];
    }

    public static function provideAuthorizedUpdateDeleteOwnRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, null],
            [UserRole::ORGANIZER, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MANAGER],
        ];
    }

    public static function provideUnAuthorizedUpdateDeleteOwnRole(): array
    {
        return [
            [UserRole::ORGANIZER, MemberRole::MEMBER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }

    public static function provideAuthorizedUpdateDeleteOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR],
            [UserRole::MANAGER],
        ];
    }

    public static function provideUnAuthorizedUpdateDeleteOtherRole(): array
    {
        return [
            [UserRole::ORGANIZER],
            [UserRole::STAFF],
        ];
    }
}
