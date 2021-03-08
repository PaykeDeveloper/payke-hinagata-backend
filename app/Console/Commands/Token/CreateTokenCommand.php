<?php

namespace App\Console\Commands\Token;

use Illuminate\Console\Command;

class CreateTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create token by email and password';

    private const TOKEN_NAME = 'cli';

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

        if (!\Auth::attempt(['email' => $email, 'password' => $password])) {
            $this->error("Unauthenticated.");
            return 1;
        }

        $user = \Auth::user();
        $user->tokens()->where('name', self::TOKEN_NAME)->delete();
        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        $this->info($token);
        return 0;
    }
}
