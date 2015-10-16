<?php

namespace MDTimezone\TestUtils;

trait SettableDataResource {
    public function setData($data) {
        $this->data = $data;
    }
}