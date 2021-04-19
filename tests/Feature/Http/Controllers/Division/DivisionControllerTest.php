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

/**
 * @group division
 */
class DivisionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private Division $division;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->division->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));

        $response = $this->getJson(route('divisions.show', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonFragment($this->division->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertNoContent();

        $result = Division::find($this->division->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsMember()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);

        $response = $this->getJson(route('divisions.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->division->toArray());
    }

    public function testShowSuccessAsMember()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->getJson(route('divisions.show', $this->division->id));

        $response->assertOk()
            ->assertJson($this->division->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccessAsMember()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->patchJson(route('divisions.update', ['division' => $this->division->id]), $data);

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
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $division2 = Division::factory()->create();

        $response = $this->getJson(route('divisions.show', $division2->id));

        $response->assertNotFound();
    }

    /**
     * viewAny 権限がないとエラー
     */
    public function testIndexNotFoundNoPermissionAsMember()
    {
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);

        $response = $this->getJson(route('divisions.index', $this->division->id));

        $response->assertNotFound();
    }

    /**
     * view 権限がないとエラー
     */
    public function testShowNotFoundNoPermissionAsMember()
    {
        Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);

        $response = $this->getJson(route('divisions.show', $this->division->id));

        $response->assertNotFound();
    }

    /**
     * update 権限がないとエラー 403
     */
    public function testUpdateForbiddenNoPermissionAsMember()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->patchJson(route('divisions.update', $this->division->id), $data);

        $response->assertForbidden();
    }

    /**
     * destroy 権限がないとエラー 403
     */
    public function testDestroyForbiddenNoPermissionAsMember()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', $this->division->id));

        $response->assertForbidden();
    }

    /**
     * update はあっても view がない場合はエラー
     */
    public function testUpdateForbiddenAsUser()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_OWN, Division::RESOURCE));

        $data = ['name' => $this->faker->name];

        $response = $this->putJson(route('divisions.update', ['division' => $this->division->id]), $data);

        $response->assertNotFound();
    }

    /**
     * destroy はあっても view がない場合はエラー
     */
    public function testDestroyForbiddenAsUser()
    {
        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::DELETE_OWN, Division::RESOURCE));

        $response = $this->deleteJson(route('divisions.destroy', ['division' => $this->division->id]));

        $response->assertNotFound();
    }
}
