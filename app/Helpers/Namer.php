<?php

namespace ChaseH\Helpers;

class Namer {
    public static function getNameOrTitle($thing) {
        if(!is_null($thing->name)) {
            return $thing->name;
        }

        if(!is_null($thing->title)) {
            return $thing->title;
        }

        return "Unknown";
    }
}