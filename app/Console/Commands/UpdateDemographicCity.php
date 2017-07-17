<?php

namespace ChaseH\Console\Commands;

use ChaseH\Models\Analytics\Demographic;
use ChaseH\Models\User;
use ChaseH\Notifications\BadCityName;
use Illuminate\Console\Command;

class UpdateDemographicCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Updates the first demographic record that doesn't have a latitude/longitude.";

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
        $demographic = Demographic::whereNull('latitude')->whereNull('longitude')->whereNotNull('city')->first();

        if($demographic == null || $demographic->city == null) {
            return $this->info("No demographics to update. We're all caught up!");
        }

        $city = urlencode($demographic->city);
        $api = config('services.google.maps_api_server');

        $url = "https://maps.googleapis.com/maps/api/geocode/json?key={$api}&address={$city}";

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);

        if($res->getStatusCode() !== 200) {
            return $this->error("Non-OK status code received.");
        }

        $data = json_decode($res->getBody());

        if($data->status == "ZERO_RESULTS") {
            // Since we don't want to continue retrying this and blocking all other tests.
            $demographic->city = null;
            $demographic->save();

            // Find the user and warn them.
            $user = User::where('demographic_id', $demographic->id)->first();
            $user->notify(new BadCityName());

            return $this->error("No results found for this city. User was notified.");
        }

        $lat = $data->results[0]->geometry->location->lat;
        $long = $data->results[0]->geometry->location->lng;

        $demographic->update([
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        return $this->info("Successfully updated a demographic's city.");
    }
}
