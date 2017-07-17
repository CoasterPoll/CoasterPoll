<?php

namespace ChaseH\Stretch;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class StretchProvider extends ServiceProvider {
    public function boot() {
        resolve(EngineManager::class)->extend('stretch', function($app) {
            return new ElasticEngine(ClientBuilder::create()
                ->setHosts(config('scout.elasticsearch.hosts'))
                ->build()
            );
        });
    }
}