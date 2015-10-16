<?php

namespace MDTimezone\TestUtils;

class MockSessionStorage extends \Resourceful\SessionStorage {

    public function startSession() {
        if ($this->container == null) {
            $this->container = [];
        }
    }

    public function setSessionData($data) {
        $this->container = $data;
    }    
}