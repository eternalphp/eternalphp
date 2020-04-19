<?php

namespace App\Admin\Controllers;
use App\Admin\Models\Menu;
use App\Admin\Models\Action;

class menuAction extends CommonAction {
	
	
	public function __construct(Menu $model,Action $action) {
		parent::__construct();
		$this->model = $model;
		$this->action = $action;
		$this->view->realtime();
	}
	
	function index(){
		$data = array();
		$data["list"] = $this->model->index()->formatList();
		$this->view('index',$data);
	}
	
	function add(){
		$data = array();
		$data["menu"] = $this->model->index()->formatList();
		$data["action"] = $this->action->select();
		$this->view('add',$data);
	}
	
	function edit(){
		$data = array();
		$data["menu"] = $this->model->index()->formatList();
		$data["action"] = $this->action->select();
		$data["row"] = $this->model->getRow();
		$this->view('add',$data);
	}
	
	function page(){
		$this->view('page');
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