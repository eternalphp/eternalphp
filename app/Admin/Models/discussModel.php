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

class discussModel extends BaseModel {
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('红锚论坛');
		$this->onQuery();
    }
	
    public function index() {
		
        $where = array();
        if (request("starttime") != '' && request("endtime") != '') {
            $starttime = request("starttime");
            $endtime = request("endtime");
            $endtime = $endtime . " 23:59:59";
            $endtime = date("Y-m-d H:i:s", strtotime($endtime));
            $where[] = "t1.createtime between '$starttime' and '$endtime'";
        }
        if (request("keyword") != '') {
            $keyword = request("keyword");
            $where[] = "t1.title like '%" . $keyword . "%'";
        }
		
		if(session("roleid")>2 && session("partyid")>0){
			$partyid = session("partyid");
			$where[] = "t1.partyid=$partyid";
		}
		
		if(request("catid")>0){
			$catid = request("catid");
			$where[] = "t1.catid=$catid";
		}
		
		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
		
        $list = $this->model->table("discuss as t1")
		->join("user as t2", "t1.userid=t2.userid")
		->field('t1.*,t2.name')
		->where($where)
		->offset(30)
		->order("newsid desc")
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		if($list){
			foreach ($list as $k => $val) {
				$list[$k]["category"] = $this->table("category")->field("title")->where("catid",$val["catid"])->getVal("title");
				$list[$k]["reads"] = $this->model->table("discuss_user")->where("newsid", $val["newsid"])->where("`read`=1")->count("userid")->getVal("count");
				$list[$k]["comments"] = $this->model->table("discuss_comment")->where("newsid", $val["newsid"])->count("userid")->getVal("count");
			}
		}
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
		$_POST["userid"] = $this->session("userid");
		if ($this->session("roleid") <= 2) {
			$_POST["create_partyid"] = 0;
		} else {
			$_POST["create_partyid"] = $this->session("partyid");
		}
		$_POST["partyid"] = $this->session("partyid");
		$newsid = $this->model->table("discuss")->insert($_POST);
		
		$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
		
		if($newsid > 0){	
			echo success("提交成功");
		}else{
			echo fail("提交失败");
		}
	}
	
	public function modify(){
		if(requestInt("newsid")>0){
			$newsid = requestInt("newsid");
			$result = $this->model->table("discuss")->where("newsid=$newsid")->update($_POST);
			
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			
			if($result){
				echo success("提交成功");
			}else{
				echo fail("提交失败");
			}
		}
	}
	
    public function remove() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
            $result = $this->model->table("discuss")->where("newsid=$newsid")->update("isdelete=1");
			
			$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();
			
            if ($result) {
                echo success(array('errmsg' => "删除成功"));
            } else {
                echo fail(array('errmsg' => "删除失败"));
            }
        }
    }
	
    public function getRow() {
        if (requestInt("newsid")>0) {
			$newsid = requestInt("newsid");
            $row = $this->model->table("discuss as t1")->join("party as t2","t1.partyid=t2.partyid")->field("t1.*,t2.name as party_name")->where("t1.newsid=$newsid")->find();
			
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			
            if($row){
				$row["name"] = $this->getUserName($row["userid"]);
				$res = $this->getReadUsers('discuss_user','newsid',$newsid);
				if($res){
					if($res["read"] || $res["unread"]){
						$row["readUser"] = implode("、",$res["read"]);
						$row["unReadUser"] = implode("、",$res["unread"]);
						$row["toUser"] = array_merge($res["read"],$res["unread"]);
						$row["toUser"] = implode("、",$row["toUser"]);
					}
				}
				$list = $this->table("discuss_comment")->field()->where("newsid=$newsid")->select();
				if($list){
					foreach($list as $k=>$val){
						$list[$k]["name"] = $this->getUserName($val["userid"]);
						if($val["parentid"]>0){
							$res = $this->table("discuss_comment")->field()->where("cid",$val["cid"])->find();
							if($res){
								$list[$k]["replyname"] = $this->getUserName($res["userid"]);
							}
						}
					}
				}
				$row["list"] = $list;
			}
			return $row;
        }
    }
 

    public function setTop() {
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$res = $this->model->table("discuss")->where("newsid=$newsid")->update("stick=1");
			
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			
			if($res){
				echo success(array('errmsg'=>'操作成功'));
			}else{
				echo fail(array('errmsg'=>'操作失败'));
			}
		}
    }
	
    public function cancelTop() {
		if(requestInt("newsid")>0){
			$newsid = requestInt("newsid");
			$res = $this->model->table("discuss")->where("newsid=$newsid")->update("stick=0");
			
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			
			if($res){
				echo success(array('errmsg'=>'操作成功'));
			}else{
				echo fail(array('errmsg'=>'操作失败'));
			}
		}
    }
	
	//屏蔽 status 2：已屏蔽 
	public function shield(){
		if(requestInt("cid") > 0){
			$cid = requestInt("cid");
			$row = $this->table("discuss_comment")->where("cid=$cid")->find();
			if($row){
				if($row["status"] == 2){
					$res = $this->model->table("discuss_comment")->where("cid=$cid")->update("status=1");
				}else{
					$res = $this->model->table("discuss_comment")->where("cid=$cid")->update("status=2");
				}
			}
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			if($res){
				echo success(array('errmsg'=>'操作成功'));
			}else{
				echo fail(array('errmsg'=>'操作失败'));
			}
		}
	}
	
	
	public function handleClose(){
		if(requestInt("id") > 0){
			$id = requestInt("id");
			$result = $this->model->table("discuss_comment")->where("cid=$id")->update("status=2");
			if($result){
				echo success();
			}else{
				echo fail();
			}
		}
	}
	
	public function handleShow(){
		if(requestInt("id") > 0){
			$id = requestInt("id");
			$result = $this->model->table("discuss_comment")->where("cid=$id")->update("status=1");
			if($result){
				echo success();
			}else{
				echo fail();
			}
		}
	}
	
	public function getCommentList(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$list = $this->model->table("discuss_comment as t1")
			->join("user as t2","t1.userid=t2.userid")
			->where("t1.newsid=$newsid")
			->field("t1.*,t2.name,t2.mobile,t2.email,t2.partyid")
			->offset(50)
			->select();
			if($list){
				
				if($list){
					foreach($list as $k=>$val){
						$list[$k]["party"] = '';
						$row = $this->model->table("party")->where("partyid",$val["partyid"])->find();
						if($row){
							$list[$k]["party"] = $row["name"];
						}
					}
				}
				
				echo success(array(
					'list'=>$list,
					'total'=>$this->model->pages['total']
				));
			}else{
				echo fail(array('errcode'=>200)); 
			}
		}
	}
	
}
?>
