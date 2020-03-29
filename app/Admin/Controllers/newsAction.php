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

class newsAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('news');
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->auth->getMenuAction();
        $data['list'] = $this->model->index();
        $url = $this->search(array('starttime','endtime','keyword','partyid','status','typeid'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
		$data["partyTree"] = $this->models("party")->getPartyAll();
		$data["newsType"] = $this->model->getNewsType();
		$data["statusList"] = array(['id'=>1,'name'=>'草稿'],['id'=>2,'name'=>'预览'],['id'=>3,'name'=>'发布']);
        $this->view('News/list', $data);
    }
	
    function add() {
		$data["newsType"] = $this->model->getNewsType();
        $this->view('News/add',$data);
    }

    function edit() {
        $data['row'] = $this->model->getRow();
		$data["newsType"] = $this->model->getNewsType();
        $this->view('News/edit',$data);
    }

    function detail() {
        $data["row"] = $this->model->getRow();
        $this->view('News/detail',$data);
    }
	
	function publish(){
		
		if(request("userids") != ''){
			$this->model->publish();
			return true;
		}
		
        if($this->session("roleid") > 2){
			$data["partyUser"] = $this->models("party")->partyUser($this->session("partyid"));
        }else{
            $data["partyUser"] = $this->models("party")->partyUser();
        }
		$data["groupUser"] = $this->models("tags")->getJson();
		$this->view("Common/publish",$data);
	}
	
    function save() {
        $this->model->save();
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
	
    //采集微信文章内容
    public function getHtml(){
		echo $this->models("handle")->getHtml();
    }

    function statistics(){
        $data['list'] = $this->model->statistics();
        $this->view("News/statistics", $data);
    }
	
	function uploadImg(){
		$this->view("Common/upload");
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
