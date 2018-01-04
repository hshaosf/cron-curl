<?php
require_once('./settings/settings.php');
require_once('./src/CronCurl.php'); 

$oCron = new CronCurl($settings);
$oCron->exec(); 
