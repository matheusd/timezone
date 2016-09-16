<?php

include("config.inc.local");
include("timezoneClient.php");

$timezone = new MDTimezoneClient();
$timezone->config = $config;