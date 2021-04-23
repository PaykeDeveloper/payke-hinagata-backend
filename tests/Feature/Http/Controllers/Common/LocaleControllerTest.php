<?php

namespace Tests\Feature\Http\Controllers\Common;

use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class LocaleControllerTest extends TestCase
{
    use RefreshSeedDatabase;

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $response = $this->getJson('api/v1/locales');

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'value',
                    'label',
                ]
            ]);
    }
}
