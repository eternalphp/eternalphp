<?php

namespace App\Admin\Controllers;

class partyAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("party");
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
	}
	
	function index(){
		$data["menuAction"] = $this->auth->getMenuAction();
		$data["partyJson"] = $this->models("party")->getPartyJson();
		$data["list"] = $this->model->index();
		$url = $this->search(array('keyword','partyid'));
		$total = $this->model->pages['count'];
		$data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = C('offset'), $pageBarNum = 5, $mode = 1);
		$data['total'] = $total;
		$this->view('list',$data);
	}
	
	function add(){
		$data["party"] = $this->models("party")->getPartyJson();
		$this->view($data);
	}
	
	function edit(){
		$data["row"] = $this->model->getRow();
		$data["party"] = $this->models("party")->getPartyJson();
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