<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Common\PermissionType;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\Project;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group division
 * @group project
 */
class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;
    private Project $project;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
        $this->member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $this->project = Project::factory()->create([
            'division_id' => $this->division->id,
        ]);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->project->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->project->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Project::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Project::RESOURCE));

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]));

        $response->assertNoContent();

        $result = Project::find($this->project->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessAsMember()
    {
        // member 経由でのアクセス
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->getJson(route('divisions.projects.index', [
            'division' => $this->division->id,
        ]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->project->toArray());
    }

    public function testShowSuccessAsMember()
    {
        // member 経由でのアクセス
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]));

        $response->assertOk()
            ->assertJson($this->project->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsMember()
    {
        // member 経由でのアクセス
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Project::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->patchJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * [準正常系]
     */

    /**
     * Memberではない別のカンパニーにアクセスするとエラーになる。
     */
    public function testShowNotFoundAsMember()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        // Division の作成とプロジェクトの作成
        /** @var Project $otherProject */
        $otherProject = Project::factory()->create();

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $otherProject->division_id,
            'project' => $otherProject->id
        ]));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->getJson(route('divisions.projects.index', $this->division->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsMember()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->patchJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsMember()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * Division の view がないとエラー
     */
    public function testIndexNotFoundNoParentViewAsUser()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Project::RESOURCE));

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertNotFound();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Project::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Project::RESOURCE));

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }

    /**
     * 親が見れないと更新できない (親チェックが view なので 404)
     */
    public function testUpdateNotFoundParentView()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Project::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Project::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * 親が見れないと削除できない (親チェックが view なので 404)
     */
    public function testDestroyNotFoundParentView()
    {
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Project::RESOURCE));
        $this->member->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Project::RESOURCE));

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }
}
