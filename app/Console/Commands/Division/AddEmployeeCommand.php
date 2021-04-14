<?php

namespace App\Console\Commands\Division;

use App\Models\Sample\Division;
use App\Models\Sample\Employee;
use App\Models\User;
use Illuminate\Console\Command;

class AddEmployeeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:add-employee {divisionId?} {email?} {roles?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add division\'s employee';

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
        $divisionId = $this->argument('divisionId');
        $email = $this->argument("email");
        $rolesString = $this->argument("roles");

        while ($divisionId === null) {
            $divisionId = $this->ask('What is the division id?');
        }

        while ($email === null) {
            $email = $this->ask('What is the email?');
        }

        /**
         * @var Division|null
         */
        $division = Division::find($divisionId);

        if (!$division) {
            $this->error('division is not found');
            return 1;
        }

        $user = User::whereEmail($email)->first();

        if (!$user) {
            $this->error('user is not found');
            return 1;
        }

        if (!is_string($rolesString)) {
            return 1;
        }

        $roles = explode(',', $rolesString);

        $employee = Employee::createWithUserAndDivision($user, $division);
        $employee->syncRoles($roles);

        return 0;
    }
}
