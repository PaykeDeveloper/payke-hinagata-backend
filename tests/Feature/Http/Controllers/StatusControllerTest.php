<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// FIXME: サンプルコードです。
class StatusControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * [正常系] データの取得ができる。
     */
    public function testShowUnAuthenticated()
    {
        $response = $this->getJson('api/v1/status');

        $response->assertOk()
            ->assertJson(['is_authenticated' => false]);
    }

    /**
     * [正常系] データの取得ができる。
     */
    public function testShowAuthenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/v1/status');

        $response->assertOk()
            ->assertJson(['is_authenticated' => true]);
    }
}
