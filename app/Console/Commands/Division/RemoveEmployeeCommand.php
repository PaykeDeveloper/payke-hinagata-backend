<?php

namespace App\Console\Commands\Division;

use App\Models\Sample\Employee;
use Illuminate\Console\Command;

class RemoveEmployeeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:remove-employee {employeeId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove an employee from a division';

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
        $employeeId = $this->argument('employeeId');

        while ($employeeId === null) {
            $employeeId = $this->ask('What is the employee id?');
        }

        /**
         * @var Employee|null
         */
        $employee = Employee::find($employeeId);

        if (!$employee) {
            $this->error('employee is not found');
            return 1;
        }

        // ロールの割り当て解除
        $employee->roles()->detach();

        $employee->delete();

        return 0;
    }
}
