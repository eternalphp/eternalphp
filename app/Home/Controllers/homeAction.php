<?php

namespace App\Home\Controllers;

class homeAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->signPackage = $this->librarys("weixinAPI")->getSignPackage();
	}
	
	function index(){
		$this->checkLogin();
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function myinfo(){
		$this->checkLogin();
		$data["row"] = $this->models("user")->getUser();
		$data["row"]["evaluate"] = $this->models("evaluate")->getNewEvaluate();
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function favorite(){
		$this->checkLogin();
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function getJson(){
		$data["list"] = $this->models('manual')->index();
		$list = $this->models("user")->getFavorite();
		
		$data["list"] = array_merge($data["list"],$list);
		
		$data["total"] = $this->model->pages['total'];
		echo json_encode($data);
	}

}

?>