<?php

namespace App\Console\Commands\Division;

use App\Models\Sample\Division;
use App\Models\Sample\Project;
use Illuminate\Console\Command;

class AddProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:add-project {divisionId?} {projectName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a project to a division';

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
        $projectName = $this->argument('projectName');

        while ($divisionId === null) {
            $divisionId = $this->ask('What is the division id?');
        }

        while ($projectName === null) {
            $projectName = $this->ask('What is the project name?');
        }

        /**
         * @var Division|null
         */
        $division = Division::find($divisionId);

        if (!$division) {
            $this->error('division is not found');
            return 1;
        }

        Project::createWithDivision($division, ['name' => $projectName]);

        return 0;
    }
}
