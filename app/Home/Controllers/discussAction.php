<?php

namespace App\Home\Controllers;

class discussAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("discuss");
		$this->signPackage = $this->weixinApi->getSignPackage();
	}
	
	function index(){
		$this->checkLogin();
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function create(){
		$this->checkLogin();
		$data["config"] = $this->signPackage;
		$this->view($data);
	}
	
	function getJson(){
		$this->checkToken();
		$data["data"] = $this->model->index();
		$data["total"] = $this->model->pages['total'];
		echo success($data);
	}
	
	function getComments(){
		$this->checkToken();
		$this->checkUserToken();
		$data["data"]  = $this->model->getComments();
		if($data["data"]){
			foreach($data["data"] as $k=>$val){
				$url = url("/discuss/doCommentLike",array('cid'=>$val["cid"]));
				$data["data"][$k]["userToken"] = $this->createUserToken($url);
			}
		}
		$data["total"] = $this->model->pages['total'];
		echo success($data);
	}
	
	function detail(){
		$this->checkLogin();
		$data["row"] = $this->model->getRow();
		$data["config"] = $this->signPackage;
		$url = url("/discuss/doLike",array('newsid'=>request("newsid")));
		$data["likeToken"] = $this->createUserToken($url);
		
		$url = url("/discuss/saveComment",array('newsid'=>request("newsid")));
		$data["commentToken"] = $this->createUserToken($url);
		
		$url = url("/discuss/getComments",array('newsid'=>request("newsid")));
		$data["getCommentsToken"] = $this->createUserToken($url);
		
		if(!$data["row"] || $data["row"]["isdelete"] == 1){
			$this->view("Error/message");
			exit;
		}
		$this->view($data);
	}
	
	function doLike(){
		$this->checkToken();
		$this->checkUserToken();
		$this->model->doLike();
	}
	
	function doCommentLike(){
		$this->checkToken();
		$this->checkUserToken();
		$this->model->doCommentLike();
	}
	
	function saveComment(){
		$this->checkToken();
		$this->checkUserToken();
		$this->model->saveComment();
	}
	
	function getDetails(){
		$this->checkToken();
		$this->model->getDetails();
	}
	
	function save(){
		$this->checkToken('HttpRequest');
		$this->model->save();
	}

}
?>
