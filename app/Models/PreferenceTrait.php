<?php

namespace ChaseH\Models;

trait PreferenceTrait {
    public function getPreferences() {
        return $this->preferences;
    }

    public function getPreference(string $key) {
        return $this->preferences[$key] ?? null; // Always at least return null
    }

    public function setPreference($new) {
        return $this->update([
            'preferences' => array_merge($this->preferences, $new),
        ]);
    }
}