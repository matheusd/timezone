<?php

namespace ToptalTimezone\TestUtils;

trait SettableDataResource {
    public function setData($data) {
        $this->data = $data;
    }
}