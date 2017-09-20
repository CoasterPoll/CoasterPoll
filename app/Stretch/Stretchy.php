<?php

namespace ChaseH\Stretch;

use Laravel\Scout\EngineManager;

trait Stretchy {
    use  \Laravel\Scout\Searchable;

    public function searchableUsing() {
        if(!config('scout.stretch') && $this->searchableUsing == "stretch") {
            return app(EngineManager::class)->engine("null");
        }

        return app(EngineManager::class)->engine($this->searchableUsing ?? "stretch");
    }

    public function searchableAsType() {
        return strtolower(str_plural($this->getTable(), 1));
    }
}