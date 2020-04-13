<?php

namespace App\Event;

use framework\Event\Event;
use App\Event\EventHandler;

class NoticeEvent extends Event{
	
	public function __construct(){
		$this->name("Notice");
		$this->handler('notice',EventHandler::class);
	}
}
?>