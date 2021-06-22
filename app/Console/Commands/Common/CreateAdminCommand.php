<?php

namespace App\Console\Commands\Common;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Common\UserRole;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create {--name=} {--email=} {--password=}';

    protected $description = 'Create user by email and password';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->option("name");
        $email = $this->option("email");
        $password = $this->option("password");
        $password_confirmation = $this->option("password");

        while ($name === null) {
            $name = $this->ask('What is the name?');
        }
        while ($email === null) {
            $email = $this->ask('What is the email?');
        }
        while ($password === null) {
            $password = $this->secret('What is the password?');
        }
        while ($password_confirmation === null) {
            $password_confirmation = $this->secret('Retype the password.');
        }

        $attributes = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ];

        try {
            $action = new CreateNewUser();
            $user = $action->create($attributes);
            $user->markEmailAsVerified();
            $user->syncRoles(UserRole::ADMINISTRATOR);
        } catch (ValidationException $e) {
            foreach ($e->validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        return 0;
    }
}
