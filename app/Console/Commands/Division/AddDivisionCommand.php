<?php

namespace App\Console\Commands\Division;

use App\Models\Division\Division;
use Illuminate\Console\Command;

class AddDivisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:add {divisionName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add division';

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
        $name = $this->argument('divisionName');

        while ($name === null) {
            $name = $this->ask('What is the division name?');
        }

        Division::create(['name' => $name]);
        return 0;
    }
}
