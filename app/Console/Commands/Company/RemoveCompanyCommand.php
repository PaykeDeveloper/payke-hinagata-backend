<?php

namespace App\Console\Commands\Company;

use App\Models\Sample\Company;
use Illuminate\Console\Command;

class RemoveCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:remove {companyId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove company';

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

        while ($companyId === null) {
            $companyId = $this->ask('What is the company id?');
        }

        /**
         * @var Company|null
         */
        $company = Company::find($companyId);

        if (!$company) {
            $this->error('company is not found');
            return 1;
        }

        $company->delete();
        return 0;
    }
}
