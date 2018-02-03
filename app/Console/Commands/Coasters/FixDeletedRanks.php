<?php

namespace ChaseH\Console\Commands\Coasters;

use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Rank;
use Illuminate\Console\Command;

class FixDeletedRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coasters:fixdeleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all deleted coasters from rankings.';

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
     * @return mixed
     */
    public function handle()
    {
        $coasters = Coaster::onlyTrashed()->get();

        Rank::whereIn('coaster_id', $coasters->pluck('id'))->delete();

        $this->output->success("Well, we made it this far. It probably did something useful.");
    }
}
