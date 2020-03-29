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

class categoryModel extends Model {
	
    public function __construct() {
        parent::__construct();
    }
	
    public function index() {
		$where = array();
		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = "title like '%".$keyword."%'";
		}
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
        return $this->model->table("category")->where($where)->offset(30)->order("catid desc")->select();
    }
	
    public function save() {
        $result = $this->model->table("category")->insert($_POST);
        if ($result) {
            echo success("操作成功");
        } else {
            echo success("操作失败");
        }
    }
	
    public function remove() {
        if (request("catid")>0) {
			$catid = request("catid");
            $result = $this->model->table("category")->where("catid=$catid")->update("isdelete=0");
            if ($result) {
                echo success(array('msg' => "删除成功"));
            } else {
                echo fail(array('msg' => "删除失败"));
            }
        }
    }
}
?>
