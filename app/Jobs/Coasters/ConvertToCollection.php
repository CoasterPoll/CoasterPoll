<?php

namespace ChaseH\Jobs\Coasters;

use ChaseH\Models\Coasters\Result;
use ChaseH\Notifications\ResultsAvailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ConvertToCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;
    protected $group;
    protected $auth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $group, $auth)
    {
        $this->filename = $filename;
        $this->group = $group;
        $this->auth = $auth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $results = json_decode(Storage::drive('local')->get($this->filename));

        foreach($results as $result) {
            Result::create([
                'coaster_id' => $result->coaster,
                'group' => $this->group,
                'percentage' => $result->percentage,
                'above' => $result->above,
                'below' => $result->below,
                'equal' => $result->equal,
                'wins' => $result->wins,
                'losses' => $result->losses,
                'ties' => $result->ties,
                'flags' => $result->flags,
            ]);
        }

        //$this->auth->notify(new ResultsAvailable($this->group, $this->filename));
    }
}
