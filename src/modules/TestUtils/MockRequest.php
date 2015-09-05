<?php

namespace ToptalTimezone\TestUtils;

class MockRequest {

    public $method;
    public $uri;
    public $body;
    public $headers = [];

    public function __construct($method = 'GET', $path = '/', $data = []) {
        $this->method = $method;
        $this->uri = new MockUri($path);
        $this->body = json_encode($data);
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['Accept'] = 'application/json';
    }

    public function getBody() {
        return $this->body;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getHeaderLine($header) {
        if (array_key_exists($header, $this->headers)) {
            return $this->headers[$header];
        }
        return '';
    }

    public function getHeader($header) {
        if (array_key_exists($header, $this->headers)) {
            if (is_array($this->headers[$header])) {
                return $this->headers[$header];
            } else {
                return [$this->headers[$header]];
            }
        }
        return [];
    }
}