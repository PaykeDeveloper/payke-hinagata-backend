<?php

namespace Tests\Feature\Console\Commands\Common;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    /**
     * [正常系]
     */

    /**
     * オプション有りでコマンドが正常終了する。
     */
    public function testAdminCreateWithOptions()
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
    public function testAdminCreateWithoutOptions()
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

    public function testAdminCreateInValid()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $name = $this->faker->userName;
        $email = $user->email;
        $password = $this->faker->slug;
        $console = $this->artisan("admin:create --name=\"$name\" --email=\"$email\" --password=\"$password\"");
        $console->assertExitCode(1);
    }
}
