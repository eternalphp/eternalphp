<?php

namespace App\Event;

use framework\Event\HandlerInterface;

class WeixinHandler implements HandlerInterface{
	
	public function __construct(){
		
	}
	
	public function handle($data = array()){
		echo __CLASS__;
		echo "\r\n";
		print_r($data);
	}
}
?>