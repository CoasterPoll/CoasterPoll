<?php

namespace ChaseH\Console\Commands;

use Carbon\Carbon;
use ChaseH\Models\Coasters\Coaster;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateRidersCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coasters:recount {--all : Skips checking for recently ridden coasters }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recounts how many riders have ridden coasters.';

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
        // Find coasters that have been ridden recently
        // OR skip if given "--all" option.
        if ($this->option('all') == null) {
            $recents = DB::table('coaster_user')->where('created_at', '>=', Carbon::now()->subMinutes(10))->get();
            $ids = array();
            foreach ($recents->pluck('coaster_id') as $id) {
                $ids[] = $id;
            }

            $coasters = Coaster::whereIn('id', $ids);
            $coasters->searchable();

            return $this->info("Marked {$coasters->count()} ready to update in Algolia.");
        }

        Coaster::all()->searchable();

        $count = Coaster::count();
        return $this->info("Marked {$count} ready to update in Algolia.");
    }
}
