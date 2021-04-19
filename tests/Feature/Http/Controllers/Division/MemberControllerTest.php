<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Division;

use App\Models\Common\PermissionType;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\MemberRole;
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
class MemberControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private Division $division;
    private Member $member;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->division = Division::factory()->create();
        $this->member = Member::factory()->create([
            'division_id' => $this->division->id,
        ]);
    }

    /**
     * [正常系]
     */

    public function testIndexSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.index', ['division' => $this->division->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->member->toArray());
    }

    public function testShowSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));

        $response = $this->getJson(route('divisions.members.show', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertOk()
            ->assertJsonFragment($this->member->toArray());
    }

    public function testUpdateSuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::UPDATE_ALL, Member::RESOURCE));

        $data = ['role_names' => [MemberRole::MEMBER]];

        $response = $this->putJson(route('divisions.members.update', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroySuccessAsUser()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE));
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::DELETE_ALL, Member::RESOURCE));

        $response = $this->deleteJson(route('divisions.members.destroy', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertNoContent();

        $result = Member::find($this->member->id);
        $this->assertNull($result);
    }

    public function testIndexSuccessUserViewAnyAsMember()
    {
        $this->user->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        /** @var Member $member */
        $member = Member::factory()->create([
            'user_id' => $this->user->id,
            'division_id' => $this->division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_ALL, Division::RESOURCE));

        $response = $this->getJson(route('divisions.members.index', [
            'division' => $this->division->id,
            'member' => $this->member->id,
        ]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($this->member->toArray())
            ->assertJsonFragment($member->toArray());
    }

    public function testShowSuccessAsMember()
    {
        $division = Division::create(['name' => $this->faker->name]);

        $member = Member::create([
            'user_id' => $this->user->id,
            'division_id' => $division->id,
        ]);
        $member->givePermissionTo(PermissionType::getName(PermissionType::VIEW_OWN, Division::RESOURCE));

        $response = $this->getJson(route('divisions.members.show', $division->id));

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

        $response = $this->patchJson(route('divisions.members.update', ['division' => $division->id]), $data);

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

        $response = $this->getJson(route('divisions.members.show', $division2->id));

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

        $response = $this->getJson(route('divisions.members.index', $division->id));

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

        $response = $this->getJson(route('divisions.members.show', $division->id));

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

        $response = $this->patchJson(route('divisions.members.update', $division->id), $data);

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

        $response = $this->deleteJson(route('divisions.members.destroy', $division->id));

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

        $response = $this->putJson(route('divisions.members.update', ['division' => $division->id]), $data);

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

        $response = $this->deleteJson(route('divisions.members.destroy', ['division' => $division->id]));

        $response->assertNotFound();
    }
}
