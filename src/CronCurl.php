<?php

class CronCurl{

	protected $settings;

	public function __construct($settings){
		if(empty($settings)){
			throw new Exception('Missing settings.');
		}
		if(!function_exists('curl_init')){
			throw new Exception('CURL not supported.');
		}
		$this->settings = $settings;
		date_default_timezone_set($this->settings['timezone']);

	}


	public function exec(){
		if(empty($this->settings['url'])){
			throw new Exception('Missing url setting.');
		}
		$url = $this->settings['url'];
		$get = array('_t'=>time());
		$url = $url. (strpos($url, '?') === FALSE ? '?' : '&'). http_build_query($get);
		echo $url; 
		$defaults = array( 
        CURLOPT_URL => $url, 
        CURLOPT_HEADER => 0, 
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_TIMEOUT => $this->settings['time_limit']
    ); 
    set_time_limit ($this->settings['time_limit']);
    $ch = curl_init(); 
    curl_setopt_array($ch, $defaults); 
    if( ! $result = curl_exec($ch)) 
    { 
    	$comment = curl_error($ch);
    	trigger_error($comment); 
    }else{
    	$comment = $result; 
    }
    curl_close($ch); 
    $this->log($this->settings['id'], $result?'OK':'NG', $comment);
	}

	protected function log($request, $status, $comment=''){
		$line = array(
			date("Ymd"), // date
			date("H:i:s"), // time
			!empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'-', // ip
			$request, // request
			$status, // status
			substr($comment, 0, 140)
		); 

		if(file_exists($this->settings['log_file'])){
			$logs = file($this->settings['log_file']); 
		}
		if(!empty($logs) && count($logs) >= $this->settings['log_limit']){
			while(count($logs) >= $this->settings['log_limit']){
				array_shift($logs); 
			}
			file_put_contents($this->settings['log_file'], implode('',$logs));
		}

		$fp = fopen($this->settings['log_file'], 'a');
		if(empty($fp)){
			trigger_error('Failed to open log file '.$this->settings['log_file']);
		}else{
			$out = fputcsv($fp, $line, "\t");
			fclose($fp);
		}
		
		return $out;

	}


}
