<?php

namespace App\Admin\Controllers;
use System\Http\Request;

class accountAction extends CommonAction{
	function __construct(){
		parent::__construct();
		$this->model = $this->models('account');
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
	}
   
	function index(){
		$data = array();
		$data["menuAction"] = $this->auth->getMenuAction();
		$data['list'] = $this->model->index();
		$url = $this->search(array('starttime','endtime','keyword'));
		$total = $this->model->pages['count'];
		$data['pagelink'] = $this->librarys('Page')->show($url,$total,$perPage=30,$pageBarNum=5,$mode=1);
		$data['total'] = $total;
		$data["partyTree"] = $this->models("party")->getPartyAll();
		$this->view('Account/list',$data);
	}
   
	function add(){
		$data["partyUser"] = $this->models("party")->partyUser();
		$data["roleList"] = $this->model->getRoleList();
		$this->view('Account/add',$data);
	}
   
	function edit(){
		$data["partyUser"] = $this->models("party")->partyUser();
		$data["roleList"] = $this->model->getRoleList();
		$data["row"] = $this->model->getRow();
		$this->view("Account/edit",$data);
	}
   
	function save(){
	   $this->model->save();
	}
   
	function remove(){
	   $this->model->remove();
	}

	function change(Request $request){
		if($request->isPost()){
			$this->model->change();
		}else{
			$this->view('Account/change');
		}
	}

}
?>