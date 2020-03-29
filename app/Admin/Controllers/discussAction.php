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

class discussAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('discuss');
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->auth->getMenuAction();
        $data['list'] = $this->model->index();
		$url = $this->search(array('starttime','endtime','keyword','partyid','catid'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view('list', $data);
    }
	
    function add() {
        $data = array();
        $this->view($data);
    }

    function edit() {
        $data["row"] = $this->model->getRow();
        $this->view($data);
    }
	
    function detail() {
        $data["row"] = $this->model->getRow();
        $this->view($data);
    }
	
    function save() {
        $this->model->save();
    }
	
	function publish(){
		
		if(request("userids")!=''){
			$this->model->publish();
			return true;
		}
		
        if($this->session("admin_type")==1){
            $data["partyUser"] = $this->models("party")->partyUser();
        }else{
            $data["partyUser"] = $this->models("party")->partyUser($this->session("party_id"));
        }
		$data["groupUser"] = $this->models("tags")->getJson();
		$this->view("Common/publish",$data);
	}
	
    function remove() {
        $this->model->remove();
    }
	
	function setTop(){
		$this->model->setTop();
	}
	
	function cancelTop(){
		$this->model->cancelTop();
	}
	
	function shield(){
		$this->model->shield();
	}

	/**
	 屏蔽评论
	 */
	function handleClose(){
		$this->model->handleClose();
	}
	
	/**
	 显示评论
	 */
	function handleShow(){
		$this->model->handleShow();
	}
	
	//评论列表
	function commentList(){
		$this->model->getCommentList();
	}
}
?>
