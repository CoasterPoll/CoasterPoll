<?php

namespace ChaseH\Console;

use ChaseH\Console\Commands\Coasters\FixDeletedRanks;
use ChaseH\Console\Commands\Coasters\RunResults;
use ChaseH\Console\Commands\FillDemographicCity;
use ChaseH\Console\Commands\UpdateDemographicCity;
use ChaseH\Console\Commands\UpdateRidersCount;
use ChaseH\Console\Commands\UserPromotion;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UserPromotion::class,
        UpdateRidersCount::class,
        UpdateDemographicCity::class,
        FillDemographicCity::class,
        RunResults::class,
        FixDeletedRanks::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('coaster:recount')->everyTenMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
