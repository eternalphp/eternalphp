<?php

namespace App\Admin\Controllers;
use App\Admin\Models\menuModel;

class menuAction extends CommonAction {
	
	
	public function __construct(menuModel $model) {
		parent::__construct();
		
		$this->model = $model;
	}
	
	function index(){
		$data = array();
		$this->view('index',$data);
	}
	
	function add(){
		$data = array();
		$data["menu"] = $this->model->getMenu();
		$this->view->realtime();
		$this->view('add',$data);
	}
	
	function edit(){

	}
	
	function detail(){
		$data = array();
		$this->view('detail',$data);
	}
	
	function save(){

	}
	
	function remove(){

	}
}


?>