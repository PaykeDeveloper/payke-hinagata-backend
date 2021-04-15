<?php

namespace App\Console\Commands\Role;

use App\Models\Role;
use Illuminate\Console\Command;

class AddRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:add {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new role';

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
        $role = $this->argument("role");
        while ($role === null) {
            $role = $this->ask('What is the role');
        }

        Role::create([
            'name' => $role,
            'guard_name' => 'web',
        ]);

        return 0;
    }
}
