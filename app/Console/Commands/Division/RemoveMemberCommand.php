<?php

namespace App\Console\Commands\Division;

use App\Models\Sample\Member;
use Illuminate\Console\Command;

class RemoveMemberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:remove-member {memberId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove an member from a division';

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
        $memberId = $this->argument('memberId');

        while ($memberId === null) {
            $memberId = $this->ask('What is the member id?');
        }

        /**
         * @var Member|null
         */
        $member = Member::find($memberId);

        if (!$member) {
            $this->error('member is not found');
            return 1;
        }

        // ロールの割り当て解除
        $member->roles()->detach();

        $member->delete();

        return 0;
    }
}
