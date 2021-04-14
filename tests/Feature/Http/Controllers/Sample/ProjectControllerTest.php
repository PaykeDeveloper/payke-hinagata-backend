<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\Company;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @group company
 * @group project
 */
class ProjectControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('PermissionSeeder');

        // 正常系ロール
        $this->artisan('role:add "Test Company Manager"');
        $projectPerms = 'viewAny_project,view_project,update_project,create_project,delete_project';
        $permissions = 'view_company,view_project,' . $projectPerms;
        $this->artisan('role:sync-permissions "Test Company Manager" ' . $permissions);

        // Company だけが見えるロール
        $this->artisan('role:add "Test Only Company Role"');
        $permissions = 'view_company';
        $this->artisan('role:sync-permissions "Test Only Company Role" ' . $permissions);

        // Company が見えないロール
        $this->artisan('role:add "Test Only Project Role"');
        $permissions = $projectPerms;
        $this->artisan('role:sync-permissions "Test Only Project Role" ' . $permissions);

        // Company の作成とプロジェクトの作成
        $this->company = Company::create(['name' => 'test']);
        $this->project = Project::createWithCompany($this->company, ['name' => 'test project']);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_company', 'viewAny_project']);

        $response = $this->getJson(route('companies.projects.index', ['company' => $this->company->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->project->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_company', 'view_project']);

        $response = $this->getJson(route('companies.projects.show', [
            'company' => $this->company->id,
            'project' => $this->project->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->project->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(['view_company', 'view_project', 'update_project']);

        $data = [ 'name' => 'foo' ];

        $response = $this->putJson(route('companies.projects.update', [
            'company' => $this->company->id,
            'project' => $this->project->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(['view_company', 'view_project', 'delete_project']);

        $response = $this->deleteJson(route('companies.projects.destroy', [
            'company' => $this->company->id,
            'project' => $this->project->id,
        ]));

        $response->assertNoContent();

        $result = Project::find($this->project->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.projects.index', [
            'company' => $this->company->id,
        ]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->project->toArray());
    }

    public function testShowSuccessAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.projects.show', [
            'company' => $this->company->id,
            'project' => $this->project->id,
        ]));

        $response->assertOk()
            ->assertJson($this->project->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Company Manager'");

        $data = ['name' => 'new project name'];

        $response = $this->patchJson(route('companies.projects.update', [
            'company' => $this->company->id,
            'project' => $this->project->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * [準正常系]
     */

    /**
     * Employeeではない別のカンパニーにアクセスするとエラーになる。
     */
    public function testShowNotFoundAsEmployee()
    {
        // Company の作成とプロジェクトの作成
        $otherCompany = Company::create(['name' => 'other test company']);
        $otherProject = Project::createWithCompany($otherCompany, ['name' => 'other test project']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.projects.show', [
            'company' => $otherCompany->id,
            'project' => $otherProject->id
        ]));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Company Role'");

        $response = $this->getJson(route('companies.projects.index', $this->company->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Company Role'");

        $response = $this->getJson(route('companies.projects.show', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Company Role'");

        $response = $this->patchJson(route('companies.projects.update', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsEmployee()
    {
        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Company Role'");

        $response = $this->deleteJson(route('companies.projects.destroy', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * Company の view がないとエラー
     */
    public function testIndexNotFoundNoParentViewAsUser()
    {
        $this->user->givePermissionTo(['viewAny_project']);

        $response = $this->getJson(route('companies.projects.index', ['company' => $this->company->id]));

        $response->assertNotFound();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $this->user->givePermissionTo(['view_company', 'update_company']);

        $data = [ 'name' => 'foo' ];

        $response = $this->putJson(route('companies.projects.update', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]), $data);

        $response->assertForbidden();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $this->user->givePermissionTo(['view_company','delete_company']);

        $response = $this->deleteJson(route('companies.projects.destroy', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]));

        $response->assertForbidden();
    }

    /**
     * 親が見れないと更新できない (親チェックが view なので 404)
     */
    public function testUpdateNotFoundParentView()
    {
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Project Role'");

        $data = [ 'name' => 'foo' ];

        $response = $this->putJson(route('companies.projects.update', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]), $data);

        $response->assertNotFound();
    }

    /**
     * 親が見れないと削除できない (親チェックが view なので 404)
     */
    public function testDestroyNotFoundParentView()
    {
        $this->artisan("company:add-employee {$this->company->id} {$this->user->email} 'Test Only Project Role'");

        $response = $this->deleteJson(route('companies.projects.destroy', [
            'company' => $this->company->id,
            'project' => $this->project->id
        ]));

        $response->assertNotFound();
    }
}
