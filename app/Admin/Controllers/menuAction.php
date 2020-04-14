<?php

namespace App\Admin\Controllers;
use App\Admin\Models\menuModel;

class menuAction extends CommonAction {
	
	
	public function __construct(menuModel $model) {
		parent::__construct();
		$this->model = $model;
		$this->view->realtime();
	}
	
	function index(){
		$data = array();
		$data["list"] = $this->model->index()->formatList();
		print_r($data["list"]);
		$this->view('index',$data);
	}
	
	function add(){
		$data = array();
		$data["menu"] = $this->model->getMenu();
		$this->view('add',$data);
	}
	
	function edit(){

	}
	
	function detail(){
		$data = array();
		$this->view('detail',$data);
	}
	
	function save(){
		$this->model->save();
	}
	
	function remove(){

	}
}


?>