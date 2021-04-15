<?php

namespace App\Console\Commands\Role;

use App\Models\Role;
use Illuminate\Console\Command;

class SyncRolePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:sync-permissions {role?} {permissions?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync role\'s permissions to the specified permissions';

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
            $permissionsRaw = $this->ask('What is permissions e.g. view_division,viewAny_division,view_project');
        }

        $permissions = explode(',', $permissionsRaw);

        $role = Role::whereName($roleName)->first();

        if (!$role) {
            $this->error('role is not found');
            return 1;
        }

        $role->syncPermissions($permissions);
        return 0;
    }
}
