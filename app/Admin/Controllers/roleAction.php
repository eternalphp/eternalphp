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
		$list = $this->model->index();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["statusText"] = ($val["status"] == 1)?'启用':'未启用';
				$list[$k]["links"] = '<a href="">编辑</a> | <a href="">删除</a>';
			}
		}
		
		$data["rows"] = $list;
		$data["total"] = $this->model->pages["count"];
		echo json_encode($data);
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