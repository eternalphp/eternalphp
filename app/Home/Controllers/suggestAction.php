<?php

namespace App\Home\Controllers;

class suggestAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("suggest");
		$this->signPackage = $this->weixinApi->getSignPackage();
	}
	
	function index(){
		$this->checkLogin();
		$data["config"] = $this->signPackage;
		$this->view("Suggest/index",$data);
	}
	
	function save(){
	    $this->checkToken('HttpRequest');
		$this->model->save();
	}

}

?>
