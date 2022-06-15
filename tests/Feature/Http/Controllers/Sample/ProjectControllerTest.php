<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Common\UserRole;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\Sample\Priority;
use App\Models\Sample\Project;
use App\Models\User;
use DateTimeZone;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

/**
 * @group division
 * @group project
 */
class ProjectControllerTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;
    private Project $project;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
        $this->member = Member::factory()->for($this->user)->for($this->division)->create();
        $this->project = Project::factory()->for($this->division)->create();
    }

    /**
     * [正常系]
     */

    /**
     * @dataProvider provideAuthorizedViewRole
     */
    public function testIndexSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['slug' => $this->project->slug]);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testStoreSuccessWithRequiredParams($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.projects.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testStoreSuccessWithFullParams($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        /** @var Member $member */
        $member = Member::factory()->for($this->division)->create();
        $startDate = $this->faker->date();
        $finishedAt = $this->faker->dateTimeBetween($startDate, '+6day')
            ->setTimezone(new DateTimeZone('Asia/Tokyo'))
            ->format("Y-m-d\TH:i:s.u\Z");
        $data = [
            'member_id' => $member->id,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'priority' => $this->faker->randomElement(Priority::values()),
            'approved' => $this->faker->boolean,
            'start_date' => $startDate,
            'finished_at' => $finishedAt,
            'difficulty' => $this->faker->numberBetween(1, 5),
            'coefficient' => $this->faker->randomFloat(1, max: 99),
            'productivity' => $this->faker->randomFloat(3, max: 999999),
        ];

        $response = $this->postJson(route('divisions.projects.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testStoreAsyncSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = ['name' => $this->faker->name];

        $response = $this->postJson("api/v1/divisions/{$this->division->id}/projects/create-async", $data);

        $response->assertNoContent();
    }

    /**
     * @dataProvider provideAuthorizedViewRole
     */
    public function testShowSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]));

        $response->assertOk()
            ->assertJsonFragment(['slug' => $this->project->slug]);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateSuccessWithSingleParam($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateSuccessWithFullParams($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        /** @var Member $member */
        $member = Member::factory()->for($this->member->division)->create();

        $startDate = $this->faker->date();
        $finishedAt = $this->faker->dateTimeBetween($startDate, '+6day')
            ->setTimezone(new DateTimeZone('Asia/Tokyo'))
            ->format("Y-m-d\TH:i:s.u\Z");
        $data = [
            'member_id' => $member->id,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'priority' => $this->faker->randomElement(Priority::values()),
            'approved' => $this->faker->boolean,
            'start_date' => $startDate,
            'finished_at' => $finishedAt,
            'difficulty' => $this->faker->numberBetween(1, 5),
            'coefficient' => $this->faker->randomFloat(1, max: 99),
            'productivity' => $this->faker->randomFloat(3, max: 999999),
        ];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateAsyncSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->patchJson(
            "api/v1/divisions/{$this->division->id}/projects/{$this->project->slug}/update-async",
            $data
        );

        $response->assertNoContent();
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testDestroySuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]));

        $response->assertNoContent();

        $result = Project::query()->find($this->project->id);
        $this->assertNull($result);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testExportSuccess($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $response = $this->get("api/v1/divisions/{$this->division->id}/projects/download");

        $response->assertOk();
    }

    /**
     * [準正常系]
     */

    /**
     * @dataProvider provideUnAuthorizedViewRole
     */
    public function testIndexUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedOtherRole
     */
    public function testStoreUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = ['name' => $this->faker->name];

        $response = $this->postJson(route('divisions.projects.store', [
            'division' => $this->division->id,
        ]), $data);

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedViewRole
     */
    public function testShowUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]));

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideUnAuthorizedOtherRole
     */
    public function testUpdateUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]), $data);

        $response->assertForbidden();
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateOptimisticLock($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        $data = [
            'name' => $this->faker->name,
            'lock_version' => $this->project->lock_version - 1,
        ];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]), $data);

        $response->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['lock_version']]);
    }

    /**
     * @dataProvider provideAuthorizedOtherRole
     */
    public function testUpdateIncorrectMember($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        if ($memberRole) {
            $this->member->syncRoles($memberRole);
        } else {
            $this->member->delete();
        }

        /** @var Member $member */
        $member = Member::factory()->create();
        $data = [
            'member_id' => $member->id,
        ];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]), $data);

        $response->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['member_id']]);
    }

    /**
     * @dataProvider provideUnAuthorizedOtherRole
     */
    public function testDestroyUnAuthorized($userRole, $memberRole)
    {
        $this->user->syncRoles($userRole);
        $this->member->syncRoles($memberRole);

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->slug,
        ]));

        $response->assertForbidden();
    }

    public function provideAuthorizedViewRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, MemberRole::MANAGER],
            [UserRole::MANAGER, MemberRole::MEMBER],
            [UserRole::STAFF, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }

    public function provideUnAuthorizedViewRole(): array
    {
        return [
            [UserRole::ORGANIZER, null],
        ];
    }

    public function provideAuthorizedOtherRole(): array
    {
        return [
            [UserRole::ADMINISTRATOR, null],
            [UserRole::MANAGER, MemberRole::MANAGER],
            [UserRole::STAFF, MemberRole::MANAGER],
        ];
    }

    public function provideUnAuthorizedOtherRole(): array
    {
        return [
            [UserRole::MANAGER, MemberRole::MEMBER],
            [UserRole::STAFF, MemberRole::MEMBER],
        ];
    }
}
