<?php

namespace ChaseH\Console\Commands\Coasters;

use Carbon\Carbon;
use ChaseH\Jobs\Coasters\ConvertToCollection;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Type;
use ChaseH\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class RunResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coasters:run {group} {type} {email=chase.hausman@me.com} {--r|lowriders} {--R|highriders} {--plr|penalize_low_riders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates results';

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
        try {
            $auth = User::where('email', $this->argument('email'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->error("Cannot find a user based on that email.");
        }
        $this->info("Starting running results.");

        try {
            $type = Type::where('name', $this->argument('type'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->error("Cannot find that type.");
        }

        $coasters = Coaster::select('id')->with(['rankings' => function($q) {
            $q->select('user_id', 'coaster_id', 'rank');
        }])->where('type_id', $type->id)->get();

        $this->calculate($coasters, $auth);
    }



    private function calculate($coasters, $auth) {
        $results = array();

        $coasters_ids = array_column($coasters->toArray(), 'id');

        $progress = $this->output->createProgressBar($coasters->count());
        foreach ($coasters as $coaster) {
            // Find all the people who've ridden $coaster
            $riders = array_column($coaster->rankings->toArray(), 'user_id');

            // Now find all the coasters that those people have ridden
            $opponents = Coaster::select('id')->where('id', '!=', $coaster->id)->whereIn('id', $coasters_ids)->whereHas('rankings', function ($q) use ($riders) {
                $q->whereIn('user_id', $riders);
            })->with(['rankings' => function ($q) {
                $q->select('user_id', 'coaster_id', 'rank');
            }])->get();

            // Reset overall coaster wins/losses/ties
            $wins = 0;
            $losses = 0;
            $ties = 0;
            $above_sum = 0;
            $below_sum = 0;
            $equal_sum = 0;

            foreach ($opponents as $opponent) {
                $partials = array();

                $above = 0;
                $below = 0;
                $equal = 0;

                // Get the rankings, indexed by voter
                foreach ($coaster->rankings as $coaster_ranking) {
                    $partials[$coaster_ranking->user_id]['coaster'] = $coaster_ranking->rank;
                }

                foreach ($opponent->rankings as $opponent_ranking) {
                    $partials[$opponent_ranking->user_id]['opponent'] = $opponent_ranking->rank;
                }

                // Go through all those rankings.
                foreach ($partials as $partial) {
                    if ((isset($partial['coaster']) && isset($partial['opponent'])) && $partial['coaster'] > $partial['opponent']) {
                        $below++;
                        $below_sum++;
                    } elseif (isset($partial['coaster']) && isset($partial['opponent']) && $partial['coaster'] == $partial['opponent']) {
                        $equal++;
                        $equal_sum++;
                    } elseif (isset($partial['coaster']) && isset($partial['opponent']) && $partial['coaster'] < $partial['opponent']) {
                        $above++;
                        $above_sum++;
                    }
                }

                if ($above > $below) {
                    $wins++;
                } elseif ($above < $below) {
                    $losses++;
                } else {
                    $ties++;
                }
            }

            if($this->option('highriders')) {
                $rider_count = collect($riders)->count();

                $wins = $wins * (0.1 * $rider_count);
            }

            if($this->option('lowriders')) {
                $rider_count = collect($riders)->count();

                if($rider_count < 10) {
                    $wins = $wins - ($rider_count * .1);
                }
            }

            if ($wins + $losses != 0) {
                $percentage = (($wins + ($ties * .5)) / ($wins + $losses + $ties)) * 100;
                $flags = null;
            } else {
                $percentage = 0;
                $flags = "Inconclusive results - no wins or loses. This is usually a result of only one rider.";
            }

            if($this->option('penalize_low_riders') && ($wins == 0 || $losses == 0)) {
                $percentage = $percentage - 2;
            }

            $results[$coaster->id] = [
                'coaster' => $coaster->id,
                'percentage' => $percentage,
                'above' => (isset($above_sum)) ? $above_sum : 0,
                'below' => (isset($below_sum)) ? $below_sum : 0,
                'equal' => (isset($equal_sum)) ? $equal_sum : 0,
                'wins' => $wins,
                'losses' => $losses,
                'ties' => $ties,
                'flags' => (isset($flags)) ? $flags : null,
            ];

            $progress->advance();
        }

        $formatted_results = json_encode($results, JSON_PRETTY_PRINT);

        $filename = "results/" . Carbon::now()->toDateTimeString() . ".json";

        Storage::disk('local')->put($filename, $formatted_results);

        $this->info("Created results file!");

        dispatch(new ConvertToCollection($filename, $this->argument('group'), $auth));
    }
}
