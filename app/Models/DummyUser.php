<?php

namespace ChaseH\Models;

class DummyUser {
    public $name;
    public $handle;
    public $id;

    public function __construct($name = "[DELETED]", $handle = "[DELETED]") {
        $this->name = $name;
        $this->handle = $handle;
        $this->id = 0;
    }

    public function getProfileLink() {
        return "#";
    }
}