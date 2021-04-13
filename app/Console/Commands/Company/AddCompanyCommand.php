<?php

namespace App\Console\Commands\Company;

use App\Models\Sample\Company;
use Illuminate\Console\Command;

class AddCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:add {companyName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add company';

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
        $name = $this->argument('companyName');

        while ($name === null) {
            $name = $this->ask('What is the company name?');
        }

        Company::create(['name' => $name]);
        return 0;
    }
}
