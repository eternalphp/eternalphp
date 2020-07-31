<?php

namespace App\Admin\Controllers;
use App\Admin\Models\Role;
use App\Admin\Services\RoleService;
use App\Admin\Services\ResponseService;

class roleAction extends CommonAction {
	
	
	public function __construct(RoleService $service,ResponseService $response) {
		parent::__construct();
		$this->service = $service;
		$this->response = $response;
		$this->view->realtime();
	}
	
	function index(){
		$this->view('index');
	}
	
	function getList(){
		$data = $this->service->getList();
		$this->response->sendJson($data);
	}
	
	function add(){
		$data = array();
		$data["menus"] = $this->service->getMenus();
		$this->view('add',$data);
	}
	
	function edit(){
		$data = array();
		$data["row"] = $this->service->getRow();
		$this->view('add',$data);
	}
	
	function detail(){
		$data = array();
		$this->view('detail',$data);
	}
	
	function save(){
		$this->service->save();
	}
	
	function remove(){

	}
}


?>