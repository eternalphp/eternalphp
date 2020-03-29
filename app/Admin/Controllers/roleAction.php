<?php

namespace App\Admin\Controllers;

class roleAction extends CommonAction{

	function __construct() {
		parent::__construct();
		$this->model = $this->models("role");
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
	}
	
	function index(){
		$data["menuAction"] = $this->auth->getMenuAction();
		$data["list"] = $this->model->index();
		$url = $this->search(array('keyword'));
		$total = $this->model->pages['count'];
		$data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = C('offset'), $pageBarNum = 5, $mode = 4);
		$data['total'] = $total;
		$this->view('list',$data);
	}
	
	function add(){
		$data["list"] = $this->model->getMenu();
		$this->view($data);
	}
	
	function edit(){
		$data["row"] = $this->model->getRow();
		$data["list"] = $this->model->getMenu();
		$this->view($data);
	}
	
	function save(){
		$this->model->save();
	}
	
	function remove(){
		$this->model->remove();
	}
}


?>