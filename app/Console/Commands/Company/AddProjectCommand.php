<?php

namespace App\Console\Commands\Company;

use App\Models\Sample\Company;
use App\Models\Sample\Project;
use Illuminate\Console\Command;

class AddProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:add-project {companyId?} {projectName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a project to a company';

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
        $projectName = $this->argument('projectName');

        while ($companyId === null) {
            $companyId = $this->ask('What is the company id?');
        }

        while ($projectName === null) {
            $projectName = $this->ask('What is the project name?');
        }

        /**
         * @var Company|null
         */
        $company = Company::find($companyId);

        if (!$company) {
            $this->error('company is not found');
            return 1;
        }

        Project::createWithCompany($company, ['name' => $projectName]);

        return 0;
    }
}
