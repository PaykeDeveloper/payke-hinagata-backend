<?php

namespace App\Console\Commands\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class RemoveRoleFromUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:remove-role {email?} {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a role from a user';

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
    public function handle()
    {
        $email = $this->argument("email");
        $role = $this->argument("role");

        while ($email === null) {
            $email = $this->ask('What is the email?');
        }
        while ($role === null) {
            $role = $this->ask('What is the role');
        }

        if ($role === 'Super Admin') {
            $this->error('Can\'t add Super Admin with this command. please use user:promote command instead');
            return 1;
        }

        $role = Role::whereName($role)->first();
        $user = User::whereEmail($email)->first();

        if (!$role || !$user) {
            $this->error('Not found "role" or "user"');
            return 1;
        }

        $user->removeRole($role);

        return 0;
    }
}
