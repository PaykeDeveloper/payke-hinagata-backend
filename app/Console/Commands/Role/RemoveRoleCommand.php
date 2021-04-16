<?php

namespace App\Console\Commands\Role;

use App\Models\Common\Role;
use Illuminate\Console\Command;

class RemoveRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:remove {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove role';

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
        $roleName = $this->argument("role");
        while ($roleName === null) {
            $roleName = $this->ask('What is the role');
        }

        $role = Role::whereName($roleName)->first();

        if (!$role) {
            $this->error('role not found');
            return 1;
        }

        if ($roleName === 'Super Admin') {
            $this->error('connot delete Super Admin role');
            return 1;
        }

        $role->delete();
        return 0;
    }
}
