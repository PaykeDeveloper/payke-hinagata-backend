<?php

namespace App\Console\Commands\Division;

use App\Models\Division\Division;
use Illuminate\Console\Command;

class RemoveDivisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:remove {divisionId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove division';

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

        while ($divisionId === null) {
            $divisionId = $this->ask('What is the division id?');
        }

        /**
         * @var Division|null
         */
        $division = Division::find($divisionId);

        if (!$division) {
            $this->error('division is not found');
            return 1;
        }

        $division->delete();
        return 0;
    }
}
