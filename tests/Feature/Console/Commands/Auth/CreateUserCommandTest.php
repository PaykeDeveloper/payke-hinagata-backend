<?php

namespace Tests\Feature\Console\Commands\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * オプション有りでコマンドが正常終了する。
     */
    public function testUserCreateWithOptions()
    {
        $name = $this->faker->userName;
        $email = $this->faker->email;
        $password = $this->faker->password;
        $console = $this->artisan("user:create --name=\"$name\" --email=$email --password=$password");
        $console->assertExitCode(0);
    }

    /**
     * オプション無しでコマンドが正常終了する。
     */
    public function testUserCreateWithoutOptions()
    {
        $email = $this->faker->email;
        $password = $this->faker->password;
        $console = $this->artisan('user:create');
        $console->expectsQuestion('What is the name?', $this->faker->userName);
        $console->expectsQuestion('What is the email?', $email);
        $console->expectsQuestion('What is the password?', $password);
        $console->expectsQuestion('Retype the password.', $password);
        $console->assertExitCode(0);
    }
}
