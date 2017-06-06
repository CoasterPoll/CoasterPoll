<?php

namespace ChaseH\Stretch;

use Laravel\Scout\EngineManager;

trait Stretchy {
    use  \Laravel\Scout\Searchable;

    public function searchableUsing() {
        return app(EngineManager::class)->engine($this->searchableUsing ?? "stretch");
    }

    public function searchableAsType() {
        return strtolower(str_plural($this->getTable(), 1));
    }
}