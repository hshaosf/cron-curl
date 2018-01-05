<?php
$settings = array();

$settings['id'] = 'default';
$settings['url'] = '';
$settings['timezone'] = 'America/Los_Angeles'; 
$settings['time_limit'] = 60;
$settings['log_limit'] = 50; 

if(!empty($_GET['id']) || !empty($argv[1])){
	$id = preg_replace('/[^-a-zA-Z0-9_]/', '', !empty($_GET['id'])?$_GET['id']:$argv[1]);
	if(!empty($id) && file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings.'.$id.'.local.php')){
		$settings['id'] = $id;
		require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings.'.$id.'.local.php'); 
	}
}

if(empty($settings['log_file'])){
	$settings['log_file'] = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR . 'cron_curl.'.$id.'.log';
}


