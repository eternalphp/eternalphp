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

class statisticsAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('statistics');
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->index();
		$url = $this->search(array('starttime','endtime','keyword','year'));
		$total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = C("offset"), $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view($data);
    }
	
    function activitys() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->activitys();
		$url = $this->search(array('starttime','endtime','keyword','year'));
		$total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = C("offset"), $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view($data);
    }
	
    function talk() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->talks();
		$url = $this->search(array('starttime','endtime','keyword','year'));
		$total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = C("offset"), $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view($data);
    }
	
	//支部情况
	function party(){
		$data["config"] = $this->signPackage;
		$data["title"] = "支部情况";
		$data["political"] = $this->model->getPolitical();
		$data["gender"] = $this->model->getGender();
		$data["age"] = $this->model->getAge();
		$data["endcation"] = $this->model->getEducation();
		$data["userCount"] = $this->model->getPartyUsers();
		$this->view("party",$data);
	}
	
}
?>
