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

class workAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('work');
		$this->auth = $this->models("auth");
		if(!$this->auth->checkAuthMethod(get('method'))){
			$this->noPowerPage();
		}
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->auth->getMenuAction();
        $data['list'] = $this->model->index();
        $url = $this->search(array('starttime','endtime','keyword','partyid'));
        $total = $this->model->pages['count'];
        $data['pagelink'] = $this->librarys('Page')->show($url, $total, $perPage = 30, $pageBarNum = 5, $mode = 1);
        $data['total'] = $total;
        $this->view('Work/list', $data);
    }
	
    function add() {
        $this->view('Work/add');
    }

    function edit() {
        $data['row'] = $this->model->getRow();
        $this->view('Work/edit',$data);
    }

    function detail() {
        $data["row"] = $this->model->getRow();
        $this->view('Work/detail',$data);
    }
	
    function save() {
        $this->model->save();
    }
	
    function remove() {
        $this->model->remove();
    }
}
?>
