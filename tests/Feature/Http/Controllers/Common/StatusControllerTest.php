<?php

namespace Tests\Feature\Http\Controllers\Common;

use App\Models\User;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class StatusControllerTest extends TestCase
{
    use RefreshSeedDatabase;

    /**
     * [正常系]
     */

    /**
     * 未認証の場合、is_authenticatedがFalse
     */
    public function testShowUnAuthenticated()
    {
        $response = $this->getJson('api/v1/status');

        $response->assertOk()
            ->assertJson(['is_authenticated' => false]);
    }

    /**
     * 認証済みの場合、is_authenticatedがTrue
     */
    public function testShowAuthenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/v1/status');

        $response->assertOk()
            ->assertJson(['is_authenticated' => true]);
    }
}
