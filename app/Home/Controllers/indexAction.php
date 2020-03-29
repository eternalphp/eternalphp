<?php

namespace App\Home\Controllers;
use framework\Controller\Controller;
use framework\Debug\Debug;
use framework\Session\Session;
use framework\Session\FileSessionHandler;

class indexAction extends Controller {
	
	private $name = '122';
	function __construct() {
		parent::__construct();
	}
	
	function index(){
		
		$data["data"] = array(
			array('id'=>1,'title'=>'标题1'),
			array('id'=>2,'title'=>'标题2'),
			array('id'=>3,'title'=>'标题3'),
			array('id'=>4,'list'=>array(
				array('id'=>1,'title'=>'标题1'),
				array('id'=>2,'title'=>'标题2'),
				array('id'=>3,'title'=>'标题3')
			))
		);
		
		cookie()->httponly()->save("name","1223");
		
		$arr = session("name");
		
		$this->view($data);
	}

}

?>