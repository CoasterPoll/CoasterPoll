<?php

namespace ChaseH\Models;

trait SearchableTrait {
    public static function look($query) {
        $search = User::take(10);
        foreach(self::getSearchable() as $field) {
            $search->orWhere($field, 'LIKE', "%{$query}%");
        }

        return $search->get();
    }
}