<?php

if ($_SERVER['REQUEST_URI'] == "/") {
    //serve index.html when accessing root during development
    fpassthru(fopen(__DIR__ . "/../docroot/index.html", "rb"));
    return;
}

if (preg_match('|^/assets/(.+)$|', $_SERVER['REQUEST_URI'], $matches)) {
    //handle assets on development

    $fname = __DIR__ .  "/../assets/" . $matches[1];
    if (!file_exists($fname)) {
        header("HTTP/1.0 404 Not Found");
        error_log("Asset Not found $fname");
        die("Not Found");
    }

    $mimetypes = array(
        'html' => 'text/html',
        'txt' => 'text/plain',
        'php' => 'application/php',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'rss' => 'application/rss+xml',
        'atom' => 'application/atom+xml',
        'gz' => 'application/x-gzip',
        'tar' => 'application/x-tar',
        'zip' => 'application/zip',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        'avi' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mov' => 'video/quicktime',
        'mp3' => 'audio/mpeg',
        'csv' => 'text/csv',
        'woff' => 'application/font-woff',
        'pdf' => 'application/pdf',
    );

    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    if (isset($mimetypes[$ext])) {
        header("Content-type: " . $mimetypes[$ext]);
    }
    
    fpassthru(fopen($fname, "rb"));
    return;
}

//just give some space on the WS console to ease the view
error_log('');
error_log('vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv');
error_log('');

//execute the request by including the production front controller
chdir(__DIR__."/../docroot");
include("front_prod.php");
