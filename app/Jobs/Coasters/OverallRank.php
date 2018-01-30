<?php

namespace ChaseH\Jobs\Coasters;

use Carbon\Carbon;
use ChaseH\Models\Coasters\Coaster;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OverallRank implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $group;
    private $auth;
    private $coasters;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $auth, $coasters = null)
    {
        $this->group = $group;
        $this->auth = $auth;
        $this->coasters = $coasters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->coasters !== null) {
            Log::info("Started running results.");
            $this->run($this->coasters);
        } else {
            $coasters = Coaster::select('id')->with(['rankings' => function($q) {
                $q->select('user_id', 'coaster_id', "rank");
            }])->get();

            $this->run($coasters);
        }
    }

    private function run($coasters) {
        set_time_limit(0);

        $results = array();

        $coasters_ids = array_column($coasters->toArray(), 'id');

        foreach($coasters as $coaster) {
            // Find all the people who've ridden $coaster
            $riders = array_column($coaster->rankings->toArray(), 'user_id');

            // Now find all the coasters that those people have ridden
            $opponents = Coaster::select('id')->where('id', '!=', $coaster->id)->whereIn('id', $coasters_ids)->whereHas('rankings', function($q) use ($riders) {
                $q->whereIn('user_id', $riders);
            })->with(['rankings' => function($q) {
                $q->select('user_id', 'coaster_id', 'rank');
            }])->get();

            // Reset overall coaster wins/losses/ties
            $wins = 0;
            $losses = 0;
            $ties = 0;
            $above_sum = 0;
            $below_sum = 0;
            $equal_sum = 0;

            foreach($opponents as $opponent) {
                $partials = array();

                $above = 0;
                $below = 0;
                $equal = 0;

                // Get the rankings, indexed by voter
                foreach($coaster->rankings as $coaster_ranking) {
                    $partials[$coaster_ranking->user_id]['coaster'] = $coaster_ranking->rank;
                }

                foreach($opponent->rankings as $opponent_ranking) {
                    $partials[$opponent_ranking->user_id]['opponent'] = $opponent_ranking->rank;
                }

                // Go through all those rankings.
                foreach($partials as $partial) {
                    if((isset($partial['coaster']) && isset($partial['opponent'])) && $partial['coaster'] > $partial['opponent']) {
                        $below++;
                        $below_sum++;
                    } elseif(isset($partial['coaster']) && isset($partial['opponent']) && $partial['coaster'] == $partial['opponent']) {
                        $equal++;
                        $equal_sum++;
                    } elseif(isset($partial['coaster']) && isset($partial['opponent']) && $partial['coaster'] < $partial['opponent']) {
                        $above++;
                        $above_sum++;
                    }
                }

                if($above > $below) {
                    $wins++;
                } elseif($above < $below) {
                    $losses++;
                } else {
                    $ties++;
                }
            }

            if($wins+$losses != 0) {
                $percentage = (($wins+($ties * .5))/($wins+$losses+$ties))*100;
                $flags = null;
            } else {
                $percentage = 0;
                $flags = "Inconclusive results - no wins or loses. This is usually a result of only one rider.";
            }

            $results[$coaster->id] = [
                'coaster' => $coaster->id,
                'percentage' => $percentage,
                'above' => (isset($above_sum)) ? $above_sum: 0,
                'below' => (isset($below_sum)) ? $below_sum: 0,
                'equal' => (isset($equal_sum)) ? $equal_sum: 0,
                'wins' => $wins,
                'losses' => $losses,
                'ties' => $ties,
                'flags' => (isset($flags)) ? $flags : null,
            ];

            Log::info("Finished coaster.");
        }

        $formatted_results = json_encode($results, JSON_PRETTY_PRINT);

        $filename = "results/".Carbon::now()->toDateTimeString().".json";

        Storage::disk('local')->put($filename, $formatted_results);

        dispatch(new ConvertToCollection($filename, $this->group, $this->auth));
    }
}
