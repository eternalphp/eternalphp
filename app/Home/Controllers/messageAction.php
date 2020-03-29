<?php

namespace App\Home\Controllers;
use App\Librarys\message;

class messageAction extends CommonAction {

	function __construct() {
		parent::__construct();
	}
	
	function send(){
		$message = new message();
		$message->title("消息通知")
		->description("测试")
		->createText('测试下发文本消息')
		->toUser(array(
			array('name'=>'青青','key'=>'320683199304053961')
		));
		$message->send();
		
	}

}

?>