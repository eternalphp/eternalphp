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

class tagsAction extends CommonAction {
    function __construct() {
        parent::__construct();
        $this->model = $this->models("tags");
    }
	
    function index() {
        $data = array();
		$data["menuAction"] = $this->models("auth")->getMenuAction();
        $data['list'] = $this->model->index();
        $this->view('Tags/list', $data);
    }
	
    function add() {
        $data = array();
        if($this->session("roleid")<=2){
            $data["partyUser"] = $this->models("party")->partyUser($partyid = 0,$userids = array(),$self = true,$chkDisabled = false);
        }else{
            $data["partyUser"] = $this->models("party")->partyUser($this->session("partyid"),$userids = array(),$self = true,$chkDisabled = false);
        }
        $this->view('Tags/add', $data);
    }
	
    function edit() {
        $data = array();
        $data["row"] = $this->model->getRow();
        if($this->session("roleid")<=2){
            $data["partyUser"] = $this->models("party")->partyUser($partyid = 0,$userids = array(),$self = true,$chkDisabled = false);
        }else{
            $data["partyUser"] = $this->models("party")->partyUser($this->session("partyid"),$userids = array(),$self = true,$chkDisabled = false);
        }
        $this->view('Tags/edit', $data);
    }
	
    function save() {
        $this->model->save();
    }
	
    function remove() {
        $this->model->remove();
    }
}
?>