<?php

namespace ChaseH\Console\Commands;

use Illuminate\Console\Command;

class FillDemographicCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:demographic-city {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the user:location command on an interval for a specified number of users.';

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
        $count = $this->argument('count');

        $this->info("Calling user:location {$count} times.");
        for($c = 0; $c <= $count; $c++) {
            $this->call('user:location');
            sleep(1);
        }
    }
}
