<?php

include("curlWrapper.php");

class ToptalTimezoneClientException extends Exception {
}

class ToptalTimezoneClient extends CurlWrapper {

    public $config;
    
    public function baseUrl() {
        return $this->config['baseUrl'];
    }
    
    protected function initCurl() {
        parent::initCurl();
        curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, __DIR__.'/cookies.local');
        curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, __DIR__.'/cookies.local');
    }
    
    protected function jsonPost($url, $data, $extraHeaders = []) {
        $res = parent::jsonPost($url, $data, $extraHeaders);
        
        if (@$res->status == 'error') {
            print_r("Server Responded with Error Msg");
            print_r($res);
            throw new ToptalTimezoneClientException("Server error: " . $res->errorMsg);
        }        
        
        return $res;
    }
    
    protected function jsonGet($url, $extraHeaders = []) {
        $res = parent::jsonGet($url, $extraHeaders);
        
        if (@$res->status == 'error') {
            print_r("Server Responded with Error Msg");
            print_r($res);
            throw new ToptalTimezoneClientException("Server error: " . $res->errorMsg);
        }        
        
        return $res;
    }
    
    public function login() {
        $this->jsonPost('/user/login', ['email' => $this->config['username'],
            'password' => $this->config['password']]);        
    }
    
    public function listTimezones() {
        $res = $this->jsonGet("/timezones");        
        return $res;
    }

}