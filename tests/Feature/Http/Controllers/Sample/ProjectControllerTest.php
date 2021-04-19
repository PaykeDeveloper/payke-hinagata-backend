<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Project;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group division
 * @group project
 */
class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        // 正常系ロール
        $this->artisan('role:add "Test Division Manager"');
        $projectPerms = 'viewAny_project,view_project,update_project,create_project,delete_project';
        $permissions = 'view_division,view_project,' . $projectPerms;
        $this->artisan('role:sync-permissions "Test Division Manager" ' . $permissions);

        // Division だけが見えるロール
        $this->artisan('role:add "Test Only Division Role"');
        $permissions = 'view_division';
        $this->artisan('role:sync-permissions "Test Only Division Role" ' . $permissions);

        // Division が見えないロール
        $this->artisan('role:add "Test Only Project Role"');
        $permissions = $projectPerms;
        $this->artisan('role:sync-permissions "Test Only Project Role" ' . $permissions);

        // Division の作成とプロジェクトの作成
        $this->division = Division::create(['name' => 'test']);
        $this->project = Project::createWithDivision($this->division, ['name' => 'test project']);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'viewAny_project']);

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->project->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'view_project']);

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->project->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'view_project', 'update_project']);

        $data = ['name' => 'foo'];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'view_project', 'delete_project']);

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
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Division Manager'");

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
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Division Manager'");

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
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Division Manager'");

        $data = ['name' => 'new project name'];

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
        // Division の作成とプロジェクトの作成
        $otherDivision = Division::create(['name' => 'other test division']);
        $otherProject = Project::createWithDivision($otherDivision, ['name' => 'other test project']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Division Manager'");

        $response = $this->getJson(route('divisions.projects.show', [
            'division' => $otherDivision->id,
            'project' => $otherProject->id
        ]));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        // member 経由でのアクセス
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Division Role'");

        $response = $this->getJson(route('divisions.projects.index', $this->division->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        // member 経由でのアクセス
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Division Role'");

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
        // member 経由でのアクセス
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Division Role'");

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
        // member 経由でのアクセス
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Division Role'");

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
        $this->user->givePermissionTo(['viewAny_project']);

        $response = $this->getJson(route('divisions.projects.index', ['division' => $this->division->id]));

        $response->assertNotFound();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'update_division']);

        $data = ['name' => 'foo'];

        $response = $this->putJson(route('divisions.projects.update', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]), $data);

        $response->assertForbidden();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $this->user->givePermissionTo(['view_division', 'delete_division']);

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * 親が見れないと更新できない (親チェックが view なので 404)
     */
    public function testUpdateNotFoundParentView()
    {
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Project Role'");

        $data = ['name' => 'foo'];

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
        $this->artisan("division:add-member {$this->division->id} {$this->user->email} 'Test Only Project Role'");

        $response = $this->deleteJson(route('divisions.projects.destroy', [
            'division' => $this->division->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }
}
