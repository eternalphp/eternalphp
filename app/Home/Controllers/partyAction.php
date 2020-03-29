<?php

namespace App\Home\Controllers;

class partyAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("party");
		$this->signPackage = $this->librarys("weixinAPI")->getSignPackage();
	}
	
	//支部信息
	function index(){
		$this->checkLogin();
		$data["count"][1] = $this->models("talk")->getTalkCount();
		$data["count"][2] = $this->models("news")->getNewsCount(6);
		//$data["count"][3] = $this->model->getNewsCount(3);
		$this->view($data);
	}
	
	function partys(){
		$this->checkLogin();
		$data["list"] = $this->model->getPartys();
		if(!$data["list"]){
			header("location:/?c=party&m=detail");
		}
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function detail(){
		$this->checkLogin();
		if(session('positionid') == 0 && (session("identityid") == 4 || session("identityid") == 5)){
			$this->noPowerPage("您没有查看权限！");
		}else{	
			$data["row"] = $this->model->getRow();
			$data["config"] = $this->signPackage;
			if($data["row"]["isdelete"] == 1){
				$this->view("Error/message");
				exit;
			}
			$this->view($data);
		}
	}

}

?>