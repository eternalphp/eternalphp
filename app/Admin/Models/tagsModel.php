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

class tagsModel extends BaseModel {
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('群组管理');
		$this->onQuery();
    }
	
    public function index() {
        $list = $this->model->table('tags')
		->where("userid",session("userid"))
		->where("isdelete=0")
		->order("tagid desc")->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		foreach($list as $k=>$val){
			$userids = $this->table("tags_user")->field("userid")->where("tagid",$val["tagid"])->toList("userid");
			$users = array();
			if($userids){
				$users = $this->table("user as t1")
				->join("party as t2","t1.partyid=t2.partyid")
				->field("CONCAT(t1.name,'(',t2.name,')') as name")
				->where("in",array('t1.userid'=>$userids))
				->where("t1.isdelete=0")
				->where("t2.isdelete=0")
				->toList("name");
			}
			$list[$k]['userCount'] = count($users);
			$list[$k]["user"] = implode("、",$users);
		}
        return $list;
    }
	
	public function getJson(){
		$list = $this->model->table('tags')
		->field("tagid as id,tagname as name")
		->where("userid",session("userid"))
		->where("isdelete=0")
		->order("tagid desc")
		->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["title"] = $val["name"];
				$list[$k]["open"] = FALSE;
				$users = $this->table("tags_user as t1")
				->join("user as t2","t1.userid=t2.userid")
				->field("t1.userid,t2.name")
				->where("t1.tagid",$val["id"])
				->where("t2.isdelete=0")
				->select();
				if($users){
					foreach($users as $kk=>$res){
						$users[$kk]["title"] = $res["name"];
						$users[$kk]["open"] = FALSE;
						$users[$kk]["icon"] = "__PUBLIC__/plugins/ztree/img/user.png";
					}
					$list[$k]["children"] = $users;
				}
			}
		}
		$data = array(
			'pId'=>0,
			'open'=>true,
			'name'=>'所有群组',
			'children'=>$list
		);
		return json_encode($data);
	}
	
	public function save(){
		if(requestInt("tagid") > 0){
			$tagid = requestInt("tagid");
			$result = $this->model->table("tags")->where("tagid=$tagid")->update($_POST);
			if($result){
				
				$this->model->table("tags_user")->where("tagid=$tagid")->delete();
				$userids = $this->getSendUsers();
				if($userids){
					$data = array();
					foreach($userids as $k=>$userid){
						$data[] = array(
							'tagid'=>$tagid,
							'userid'=>$userid
						);
					}
					if($data) $this->table("tags_user")->insert($data,true);
				}
				
				echo success("更新成功");
			}else{
				echo fail("更新失败");
			}
			
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuerys())->where($this->getConditions())->write();
			
		}else{
			$_POST["userid"] = session("userid");
			$_POST["createtime"] = date("Y-m-d H:i:s");
			$tagid = $this->model->table("tags")->insert($_POST);
			if($tagid){
				$userids = $this->getSendUsers();
				if($userids){
					$data = array();
					foreach($userids as $k=>$userid){
						$data[] = array(
							'tagid'=>$tagid,
							'userid'=>$userid
						);
					}
					if($data) $this->table("tags_user")->insert($data,true);
				}
				echo success("创建成功");
			}else{
				echo fail("创建失败");
			}
			
			$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuerys())->where($this->getConditions())->write();
			
		}
	}
	
	public function getRow(){
		if(requestInt("tagid") > 0){
			$tagid = requestInt("tagid");
			$row = $this->model->table("tags")->field()->where("tagid=$tagid")->find();
			if($row){
				$row["userids"] = $this->model->table("tags_user")->where("tagid=$tagid")->toList("userid");
				$row["userids"] = implode("|",$row["userids"]);
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
			return $row;
		}
	}
	
	public function remove(){
		if(requestInt("tagid") > 0){
			$tagid = requestInt("tagid");
			$result = $this->model->table("tags")->where("tagid=$tagid")->update("isdelete=1");
			
			$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();
			
			if($result){
				echo success(array('msg'=>'删除成功'));
			}else{
				echo fail(array('msg'=>'删除失败'));
			}
		}
	}
}
?>
