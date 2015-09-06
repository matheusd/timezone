<?php

class CurlWrapperException extends Exception { } 

/**
 * An utility class to wrap a persistent (cookie preserving) curl session.
 */
class CurlWrapper {    

    protected $curlHandle;

    public function __construct() {
        $this->curlHandle = null;
    }

    public function __destruct() {
        if (!is_null($this->curlHandle))
            curl_close($this->curlHandle);
    }

    /**
     * Returns the base url (if needed) for all subsequent accesss to
     * services. This is prepended on all calls to access(), get(), post() and
     * similar.
     */
    protected function baseUrl() {
        return "";
    }

    protected function defaultHeaders() {
        return [];
    }

    protected function initCurl() {
        $this->curlHandle = curl_init();
    }

    
    protected function access($url, $data, $headers = null) {
        if (is_null($this->curlHandle)) {
            $this->initCurl();
        }
        $ch = $this->curlHandle;        
        
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl() . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        if (!$headers) $headers = $this->defaultHeaders ();
        $strHeaders = [];
        foreach ($headers as $k => $v) {
            $strHeaders[] = "$k: $v";
        }
        if ($strHeaders) {
            //error_log("Str headers: " . implode("\n", $strHeaders));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $strHeaders);
        }

        if (is_array($data)) {
            $newData = "";
            foreach ($data as $k => $v) {
                $newData .= "$k=$v&";
            }
            $data = $newData;
        }

        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);            
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        $output = curl_exec($ch);        
        if (!$output) {
            $error = curl_error($ch);            
            if ($error) {
                throw new CurlWrapperException($error);
            }
        }

        return $output;
    }

    protected function get($url, $headers = null) {
        return $this->access($url, null, $headers);
    }

    protected function post($url, $data, $headers = null) {
        return $this->access($url, $data, $headers);
    }

    protected function xmlPost($url, $data) {
        $res = $this->post($url, $data);
        if (!$res) {
            throw new Exception("Error on remote connection");
        }
        $doc = new DOMDocument();
        $doc->loadXML($res);
        return $doc;
    }
    
    protected function jsonPost($url, $data, $extraHeaders = []) {
        if (!is_string($data))
            $data = json_encode($data);
        $headers = array_merge($extraHeaders,
                ["Accept" => "application/json",
                 "Content-Type" => "application/json"]);
        $jsonRes = $this->post($url, $data, $headers);        
        $res = json_decode($jsonRes);
        $err = json_last_error();
        if ($err !== JSON_ERROR_NONE) {
            $exc = new CurlWrapperException("Json Decoding Error:\n" . $jsonRes);
            $exc->data = $jsonRes;
            throw $exc;
        }
        return $res;
    }

    protected function jsonGet($url, $extraHeaders = []) {        
        $headers = array_merge($extraHeaders,
                ["Accept" => "application/json",
                 "Content-Type" => "application/json"]);
        $jsonRes = $this->get($url, $headers);
        $res = json_decode($jsonRes);
        return $res;
    }

}
