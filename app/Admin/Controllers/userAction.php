<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

namespace App\Admin\Controllers;

class userAction extends CommonAction {
    function __construct() {
        parent::__construct();
        $this->model = $this->models('user');
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->auth->getMenuAction();
        $data['list'] = $this->model->index();
        $url = $this->search(array('keyword','partyid','status','positionid'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $data["partyTree"] = $this->models("party")->getPartyAll();
		$data["positionList"] = $this->model->getPosition();
        $this->view('User/list', $data);
    }
	
    function add() {
        $data = array();
        $data["party"] = $this->models("party")->getJson();
		$data["position"] = $this->model->getPosition();
		$data["identity"] = $this->model->getIdentity();
		$data["political"] = $this->model->getPolitical();
		$data["education"] = $this->model->getEducation();
        $this->view('User/add', $data);
    }
	
    function edit() {
        $data = array();
        $data["party"] = $this->models("party")->getJson();
		$data["position"] = $this->model->getPosition();
		$data["identity"] = $this->model->getIdentity();
		$data["political"] = $this->model->getPolitical();
		$data["education"] = $this->model->getEducation();
        $data["row"] = $this->model->getRow();
        $this->view('User/edit', $data);
    }
	
	function selectParty(){
		$data["partyAll"] = $this->models("party")->getJson();
		$this->view("Common/selectParty",$data);
	}
	
    function detail() {
        $data["row"] = $this->model->getRow();
        $this->view("User/view", $data);
    }
	
    function save() {
        $this->model->save();
    }
	
    function remove() {
        $this->model->remove();
    }
	
	//设置党费
	function memberFee(){
		$this->view();
	}
	
	function saveFee(){
		$this->model->saveFee();
	}
	
	//修改部门
	function modifyParty(){
		$data["partyTree"] = $this->models("party")->getPartyAll();
		$this->view($data);
	}
	
	function saveModifyParty(){
		$this->model->saveModifyParty();
	}
	
	//修改排序
	function handleSort(){
		$data["list"] = $this->model->getSortUser();
		$this->view($data);
	}
	
	function saveHandleSort(){
		$this->model->saveHandleSort();
	}
	
	function importFile(){
		$this->view("import");
	}
	
	//导入数据
	function importData(){
		$this->model->importData();
	}
	
	function saveData(){
		$this->model->saveData();
	}
	
	//导出数据
	function exportFile(){
		$this->model->exportFile();
	}
}
?>
