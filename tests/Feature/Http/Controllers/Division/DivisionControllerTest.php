<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Division;

use App\Models\Common\PermissionType;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\User;
use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function route;

/**
 * @group division
 */
class DivisionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $division = Division::create(['name' => $this->faker->name]);

        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($division->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $division = Division::create(['name' => 'test']);

        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));

        $response = $this->getJson(route('divisions.show', ['division' => $division->id]));

        $response->assertOk()
            ->assertJsonFragment($division->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNoContent();

        $result = Division::find($division->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);

        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($division->toArray());
    }

    public function testShowSuccessAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);

        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->getJson(route('divisions.show', $division->id));

        $response->assertOk()
            ->assertJson($division->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);

        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

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
        $division = Division::create(['name' => $this->faker->name]);
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $division2 = Division::create(['name' => $this->faker->name]);

        $response = $this->getJson(route('divisions.show', $division2->id));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);

        $response = $this->getJson(route('divisions.index', $division->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);

        $response = $this->getJson(route('divisions.show', $division->id));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->patchJson(route('divisions.update', $division->id), $data);

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', $division->id));

        $response->assertForbidden();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $division->id]), $data);

        $response->assertNotFound();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $division = Division::create(['name' => $this->faker->name]);
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::DELETE_OWN, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $division->id]));

        $response->assertNotFound();
    }
}
