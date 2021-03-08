<?php

namespace App\Console\Commands\User;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user by email and password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $email = $this->argument("email");
        $password = $this->argument("password");

        while ($email === null) {
            $email = $this->ask('What is the email?');
        }
        while ($password === null) {
            $password = $this->secret('What is the password?');
        }

        $name = is_string($email) ? strstr($email, '@', true) : null;

        $attributes = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        try {
            $action = new CreateNewUser();
            $action->create($attributes);
        } catch (ValidationException $e) {
            foreach ($e->validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        return 0;
    }
}
