<?php

namespace App\Console\Commands\Role;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AppendRolePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:append-permissions {role?} {permissions?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append permissions to a role';

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
        $permissionsRaw = $this->argument('permissions');

        while ($roleName === null) {
            $roleName = $this->ask('What is the role');
        }

        while ($permissionsRaw === null) {
            $permissionsRaw = $this->ask('What is permissions e.g. view_company,viewAny_company,view_project');
        }

        $permissions = explode(',', $permissionsRaw);

        $role = Role::whereName($roleName)->first();

        if (!$role) {
            $this->error('role is not found');
            return 1;
        }

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return 0;
    }
}
