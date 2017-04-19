<?php

namespace ChaseH\Jobs;

use ChaseH\Models\Coasters\Rank;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class UpdateRanking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $input;
    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input, $user_id)
    {
        $this->input = $input;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->input as $request) {
            Rank::where('coaster_id', $request['coaster'])
                ->where('user_id', $this->user_id)
                ->update(['rank' => $request['rank']]);
        }

        Cache::forget('ranked:'.$this->user_id);
        Cache::forget('unranked:'.$this->user_id);
    }
}
