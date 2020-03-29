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

namespace App\Admin\Models;
use System\Core\Model;

class manualModel extends BaseModel{
	
    public function __construct() {
        parent::__construct();
    }
	
    public function index() {
        $where = array();
        if (request("starttime") != '' && request("endtime") != '') {
            $starttime = request("starttime");
            $endtime = request("endtime");
            $endtime = $endtime." 23:59:59";
            $where[] = "createtime between '$starttime' and '$endtime'";
        }
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "title like '%" . $keyword . "%'";
        }

		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('manual')
		->where($where)
		->offset(30)
		->order("createtime desc")->select();
        return $list;
    }
	
    public function save() {
		if(requestInt("newsid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
    }
	
	public function create(){
		$_POST["createtime"] = date("Y-m-d H:i:s");
		$newsid = $this->model->table("manual")->insert($_POST);
		if ($newsid > 0) {
			echo success("提交成功");
		} else {
			echo fail("提交失败");
		}
	}
	
	public function modify(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$result = $this->model->table("manual")->where("newsid=$newsid")->update($_POST);
			if ($result) {
				echo success("提交成功");
			} else {
				echo fail("提交失败");
			}
		}	
	}
	
    public function remove() {
        if (requestInt("newsid")>0) {
			$newsid = requestInt("newsid");
            $result = $this->model->table("manual")->where("newsid=$newsid")->update("isdelete=1");
            if ($result) {
                echo success(array('errmsg' => "删除成功"));
            } else {
                echo fail(array('errmsg' => "删除失败"));
            }
        }
    }
	
    public function getRow() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
            return $this->model->table("manual")->where("newsid=$newsid")->find();
        }
    }
}
?>
