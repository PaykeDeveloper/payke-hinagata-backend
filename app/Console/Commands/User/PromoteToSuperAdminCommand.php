<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class PromoteToSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote the specified user to the super admin (Append Super admin role)';

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

        while ($email === null) {
            $email = $this->ask('What is the email?');
        }

        $role = Role::whereName('Super Admin')->first();
        $user = User::whereEmail($email)->first();

        if (!$role || !$user) {
            $this->error('User is not found');
            return 1;
        }

        $user->assignRole($role);

        return 0;
    }
}
