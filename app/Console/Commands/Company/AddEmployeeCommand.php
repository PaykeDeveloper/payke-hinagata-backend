<?php

namespace App\Console\Commands\Company;

use App\Models\Sample\Company;
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
    protected $signature = 'company:add-employee {companyId?} {email?} {roles?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add company\'s employee';

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
        $companyId = $this->argument('companyId');
        $email = $this->argument("email");
        $rolesString = $this->argument("roles");

        while ($companyId === null) {
            $companyId = $this->ask('What is the company id?');
        }

        while ($email === null) {
            $email = $this->ask('What is the email?');
        }

        /**
         * @var Company|null
         */
        $company = Company::find($companyId);

        if (!$company) {
            $this->error('company is not found');
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

        $employee = Employee::createWithUserAndCompany($user, $company);
        $employee->syncRoles($roles);

        return 0;
    }
}
