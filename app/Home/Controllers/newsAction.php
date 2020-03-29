<?php

namespace App\Home\Controllers;

class newsAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("news");
		$this->signPackage = $this->weixinApi->getSignPackage();
	}
	
	function index(){
		$this->checkLogin();
		
		$data["config"] = $this->signPackage;
		$this->view("News/list",$data);
	}
	
	function getJson(){
	    $this->checkToken();
		$data["data"] = $this->model->index();
		$data["total"] = $this->model->pages['total'];
		echo success($data);
	}
	
	function detail(){
		$this->checkLogin();

		$data["row"] = $this->model->getRow();
		$data["config"] = $this->signPackage;
		
		$url = url("/news/doLike",array('newsid'=>request("newsid")));
		$data["userToken"] = $this->createUserToken($url);
		
		$url = url("/news/getComments",array('newsid'=>request("newsid")));
		$data["getCommentsToken"] = $this->createUserToken($url);
		
		if(!$data["row"] || $data["row"]["isdelete"] == 1){
			$this->view("Error/message");
			exit;
		}
		$this->view("News/detail",$data);
	}
	
	function preview(){
		$this->checkLogin();

		$data["row"] = $this->model->getRow();
		$data["config"] = $this->signPackage;
		
		$url = url("/news/doLike",array('newsid'=>request("newsid")));
		$data["userToken"] = $this->createUserToken($url);
		
		$url = url("/news/getComments",array('newsid'=>request("newsid")));
		$data["getCommentsToken"] = $this->createUserToken($url);
		
		if(!$data["row"] || $data["row"]["isdelete"] == 1){
			$this->view("Error/message");
			exit;
		}
		$this->view("News/detail",$data);
	}
	
	function getComments(){
	    $this->checkToken();
		$this->checkUserToken();
		$data["data"] = $this->model->getComments();
		$data["total"] = $this->model->pages['total'];
		echo success($data);
	}
	
	function doLike(){
	    $this->checkToken();
		$this->checkUserToken();
		$this->model->doLike();
	}
	
	function comment(){
	    $this->checkLogin();
		$data["row"] = $this->model->getRow();
		$url = url("/news/saveComment",array('newsid'=>request("newsid")));
		$data["userToken"] = $this->createUserToken($url);
		$this->view("News/comment",$data);
	}
	
	function saveComment(){
	    $this->checkToken();
		$this->checkUserToken();
		$this->model->saveComment();
	}

}
?>