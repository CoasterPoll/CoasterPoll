<?php

namespace ChaseH\Helpers;

use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Manufacturer;
use ChaseH\Models\Coasters\Park;
use ChaseH\Models\Sharing\Link;

class CPID {
    private $prefix;
    private $id;
    public $thing = null;

    public function __construct($cid) {
        if($cid != null) {
            $this->prefix = substr($cid, 0, 1);
            $this->id = substr($cid, 1);

            $this->thing = $this->findThing($this->prefix, $this->id);
        }
    }

    public function __toString() {
        return $this->prefix.$this->id;
    }

    public function findThing($prefix, $id) {
        switch ($prefix) {
            case "L":
                return Link::where('id', $id)->first();
                break;
            case "P":
                return Park::where('id', $id)->first();
                break;
            case "C":
                return Coaster::where('id', $id)->first();
                break;
            case "M":
                return Manufacturer::where('id', $id)->first();
                break;
            default:
                return null;
        }
    }

    public function getClassName() {
        if($this->thing !== null) {
            return get_class($this->thing);
        }

        return "";
    }

    public function getNameOrTitle() {
        // Things with titles
        if(in_array($this->prefix, ['L'])) {
            return $this->thing->title;
        }

        if(in_array($this->prefix, ['C', 'P', 'M'])) {
            return $this->thing->name;
        }

        return "Unknown";
    }
}