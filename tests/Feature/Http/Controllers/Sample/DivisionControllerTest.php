<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\Division;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @group division
 */
class DivisionControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        // ロールの作成
        $this->artisan('role:add "Test Division Manager"');
        $this->artisan('role:sync-permissions "Test Division Manager" viewAny_division,view_division,update_division');

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['viewAnyAll_division']);

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($division->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['viewAll_division']);

        $response = $this->getJson(route('divisions.show', ['division' => $division->id]));

        $response->assertOk()
            ->assertJsonFragment($division->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['view_division', 'update_division']);

        $data = ['name' => 'foo'];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['view_division', 'delete_division']);

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNoContent();

        $result = Division::find($division->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email} 'Test Division Manager'");

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($division->toArray());
    }

    public function testShowSuccessAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email} 'Test Division Manager'");

        $response = $this->getJson(route('divisions.show', $division->id));

        $response->assertOk()
            ->assertJson($division->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email} 'Test Division Manager'");

        $data = ['name' => 'new division name'];

        $response = $this->patchJson(route('divisions.update', ['division' => $division->id]), $data);

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
        $division = Division::create(['name' => 'test']);
        $division2 = Division::create(['name' => 'another']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email} 'Test Division Manager'");

        $response = $this->getJson(route('divisions.show', $division2->id));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email}");

        // role を削除
        $division->findMembersByUser($this->user)[0]->roles()->detach();

        $response = $this->getJson(route('divisions.index', $division->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email}");

        // role を削除
        $division->findMembersByUser($this->user)[0]->roles()->detach();

        $response = $this->getJson(route('divisions.show', $division->id));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email}");

        $response = $this->patchJson(route('divisions.update', $division->id));

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsMember()
    {
        $division = Division::create(['name' => 'test']);

        // member 経由でのアクセス
        $this->artisan("division:add-member {$division->id} {$this->user->email}");

        $response = $this->deleteJson(route('divisions.destroy', $division->id));

        $response->assertForbidden();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['update_division']);

        $data = ['name' => 'foo'];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertForbidden();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(['delete_division']);

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertForbidden();
    }
}
