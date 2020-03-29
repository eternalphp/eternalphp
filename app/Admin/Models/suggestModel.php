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
use App\Librarys\SLSLog;

class suggestModel extends BaseModel{
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('意见建议');
		$this->onQuery();
    }
	
	/**
	 * 获取数据列表
	 */
    public function index() {
        $where = array();
        if (request("starttime") != '' && request("endtime") != '') {
            $starttime = request("starttime");
            $endtime = request("endtime");
            $endtime = $endtime." 23:59:59";
            $where[] = "t1.createtime between '$starttime' and '$endtime'";
        }
		
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "t1.content like '%" . $keyword . "%'";
        }

		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('suggest as t1')
		->join("user as t2","t1.userid=t2.userid")
		->where($where)
		->field("t1.*,t2.mobile,t2.name")
		->offset(30)
		->order("t1.createtime desc")
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
        return $list;
    }
	
	/**
	 * 删除数据
	 */
    public function remove() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
            $result = $this->model->table("suggest")->where("newsid=$newsid")->update("isdelete=1");
			$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();
            if ($result) {
                echo success(array('errmsg' => "删除成功"));
            } else {
                echo fail(array('errmsg' => "删除失败"));
            }
        }
    }
	
	/**
	 * 获取单行数据
	 */
    public function getRow() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
            $row = $this->model->table("suggest as t1")
			->join("user as t2","t1.userid=t2.userid")
			->where("t1.newsid=$newsid")
			->field("t1.*,t2.name,t2.mobile")
			->find();
			$row["files"] = array();
			if($row["filename"] != ''){
				$row["files"] = explode("|",$row["filename"]);
			}
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			return $row;
        }
    }
	
	/**
	 * 回复留言
	 */
	public function reply(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$result = $this->model->table("suggest")->where("newsid=$newsid")->update(array(
				'reply'=>$_POST["reply"],
				'status'=>1
			));
			$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
			if($result){
				echo success("提交成功");
			}else{
				echo fail("提交失败");
			}
		}
	}
}
?>
