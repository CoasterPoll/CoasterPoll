<?php

namespace ChaseH\Models;

trait SearchableTrait {
    public static function look($query) {
        $search = self::take(10);

        if(ends_with($query, "--locked")) {
            $query = str_replace(" --locked", "", $query);
            $trashed = true;
        }

        foreach(self::getSearchable() as $field) {
            $search->orWhere($field, 'LIKE', "%{$query}%");
        }

        if(isset($trashed)) {
            $search->withTrashed();
        }

        return $search->get();
    }
}