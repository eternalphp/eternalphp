<?php

namespace App\Admin\Controllers;
use App\Admin\Models\Role;

class roleAction extends CommonAction {
	
	
	public function __construct(Role $model) {
		parent::__construct();
		$this->model = $model;
		$this->view->realtime();
	}
	
	function index(){
		$data = array();
		$data["list"] = $this->model->index();
		$this->view('index',$data);
	}
	
	function getList(){
		$data["rows"] = $this->model->index();
		$data["total"] = $this->model->pages["total"];
		$data["totalNotFiltered"] = $this->model->pages["total"];
		echo json_encode($data["rows"]);
	}
	
	function add(){
		$data = array();
		$this->view('add',$data);
	}
	
	function edit(){
		$data = array();
		$data["row"] = $this->model->getRow();
		$this->view('add',$data);
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