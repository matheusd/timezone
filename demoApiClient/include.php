<?php

include("config.inc.local");
include("timezoneClient.php");

$timezone = new ToptalTimezoneClient();
$timezone->config = $config;