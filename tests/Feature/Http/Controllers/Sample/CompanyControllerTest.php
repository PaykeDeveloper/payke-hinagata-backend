<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @group company
 */
class CompanyControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('PermissionSeeder');

        // ロールの作成
        $this->artisan('role:add "Test Company Manager"');
        $this->artisan('role:sync-permissions "Test Company Manager" viewAny_company,view_company,update_company');

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['viewAnyAll_company']);

        $response = $this->getJson(route('companies.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($company->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['viewAll_company']);

        $response = $this->getJson(route('companies.show', ['company' => $company->id]));

        $response->assertOk()
            ->assertJsonFragment($company->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['view_company', 'update_company']);

        $data = [ 'name' => 'foo' ];

        $response = $this->putJson(route('companies.update', ['company' => $company->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['view_company', 'delete_company']);

        $response = $this->deleteJson(route('companies.destroy', ['company' => $company->id]));

        $response->assertNoContent();

        $result = Company::find($company->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($company->toArray());
    }

    public function testShowSuccessAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.show', $company->id));

        $response->assertOk()
            ->assertJson($company->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email} 'Test Company Manager'");

        $data = ['name' => 'new company name'];

        $response = $this->patchJson(route('companies.update', ['company' => $company->id]), $data);

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
        $company = Company::create(['name' => 'test']);
        $company2 = Company::create(['name' => 'another']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email} 'Test Company Manager'");

        $response = $this->getJson(route('companies.show', $company2->id));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email}");

        $response = $this->getJson(route('companies.index', $company->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email}");

        $response = $this->getJson(route('companies.show', $company->id));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email}");

        $response = $this->patchJson(route('companies.update', $company->id));

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsEmployee()
    {
        $company = Company::create(['name' => 'test']);

        // employee 経由でのアクセス
        $this->artisan("company:add-employee {$company->id} {$this->user->email}");

        $response = $this->deleteJson(route('companies.destroy', $company->id));

        $response->assertForbidden();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['update_company']);

        $data = [ 'name' => 'foo' ];

        $response = $this->putJson(route('companies.update', ['company' => $company->id]), $data);

        $response->assertForbidden();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $company = Company::create(['name' => 'test']);

        $this->user->givePermissionTo(['delete_company']);

        $response = $this->deleteJson(route('companies.destroy', ['company' => $company->id]));

        $response->assertForbidden();
    }
}
