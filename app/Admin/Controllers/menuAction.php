<?php

namespace App\Admin\Controllers;
use App\Admin\Models\Menu;

class menuAction extends CommonAction {
	
	
	public function __construct(Menu $model) {
		parent::__construct();
		$this->model = $model;
		$this->view->realtime();
		$this->authMenu = $this->model->index()->getAuthMenu();
		cache("menus",$this->authMenu);
	}
	
	function index(){
		$data = array();
		$data["list"] = $this->model->index()->formatList();
		$this->view('index',$data);
	}
	
	function add(){
		$data = array();
		$data["menu"] = $this->model->index()->formatList();
		$this->view('add',$data);
	}
	
	function edit(){
		$data = array();
		$data["menu"] = $this->model->index()->formatList();
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