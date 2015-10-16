<?php

namespace MDTimezone\TestUtils;

class MockLogger {

    public $logMessages = [
        'info' => [],
        'debug' => [],
        'warn' => [],
        'error' => []
    ];

    public function info($str) {
        $this->logMessages['info'][] = $str;
    }

    public function notice($str) {
        $this->logMessages['notice'][] = $str;
    }

    public function error($str) {
        $this->logMessages['error'][] = $str;
    }

}