<?php

namespace App\Event;

use framework\Event\Event;
use App\Event\EventHandler;

class WeixinEvent extends Event{
	
	public function __construct(){
		$this->name("Weixin");
		$this->handler('weixin',EventHandler::class);
	}
}
?>