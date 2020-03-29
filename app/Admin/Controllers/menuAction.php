<?php

namespace App\Admin\Controllers;

class menuAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("menu");
	}
	
	function index(){
		$data["menuAction"] = $this->models("auth")->getMenuAction();
		$data["list"] = $this->model->index();
		$this->view('list',$data);
	}
	
	function add(){
		$data["list"] = $this->model->topMenu();
		$data["action"] = $this->model->getAction();
		$this->view($data);
	}
	
	function edit(){
		$data["list"] = $this->model->topMenu();
		$data["action"] = $this->model->getAction();
		$data["row"] = $this->model->getRow();
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