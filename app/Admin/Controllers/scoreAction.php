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

class scoreAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('score');
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->index();
        $url = $this->search(array('starttime','endtime','keyword'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view('Score/list', $data);
    }
	
    function member() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->member();
        $url = $this->search(array('starttime','endtime','keyword'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view('Score/member', $data);
    }
	
    function party() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->party();
        $url = $this->search(array('starttime','endtime','keyword'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view('Score/party', $data);
    }
	
	function seeting(){
		$data["list"] = $this->model->getScoreRules(); 
		$this->view($data);
	}
	
	function save(){
		$this->model->save();
	}
	
}
?>
