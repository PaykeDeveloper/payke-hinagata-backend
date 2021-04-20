<?php

namespace Tests\Feature\Console\Commands\Common;

use Database\Seeders\Common\PermissionSeeder;
use Database\Seeders\Common\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
    }

    /**
     * [正常系]
     */

    /**
     * オプション有りでコマンドが正常終了する。
     */
    public function testUserCreateWithOptions()
    {
        $name = $this->faker->userName;
        $email = $this->faker->unique()->email;
        $password = $this->faker->slug;
        $console = $this->artisan("admin:create --name=\"$name\" --email=\"$email\" --password=\"$password\"");
        $console->assertExitCode(0);
    }

    /**
     * オプション無しでコマンドが正常終了する。
     */
    public function testUserCreateWithoutOptions()
    {
        $email = $this->faker->unique()->email;
        $password = $this->faker->password(minLength: 8);
        $console = $this->artisan('admin:create');
        $console->expectsQuestion('What is the name?', $this->faker->userName);
        $console->expectsQuestion('What is the email?', $email);
        $console->expectsQuestion('What is the password?', $password);
        $console->expectsQuestion('Retype the password.', $password);
        $console->assertExitCode(0);
    }
}
