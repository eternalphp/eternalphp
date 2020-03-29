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

class partyModel extends Model{

    public function __construct(SLSLog $SLSLog){
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('部门管理');
		$this->onQuery();
    }
	
    public function index() {
		
		$where = array();
		if(request("keyword") != ''){
			$keyword = request('keyword');
			$where[] = "name like '%$keyword%'";
		}
		
		if(requestInt("parentid") > 0){
			$parentid = requestInt("parentid");
			$partyids = $this->getChildId($parentid);
			$where[] = "partyid in (".implode(",",$partyids).")";
		}
		
		if(session("roleid") > 2){
			$partyid = session("partyid");
			$partyids = $this->getChildId($partyid);
			$where[] = "partyid in (".implode(",",$partyids).")";
		}
		
		$party = $this->model->table("party")->field('partyid,name')->toList('partyid','name');
		
		$where[] = "isdelete=0";
		$where = implode(' and ',$where);
		$list = $this->model->table("party")->field()->where($where)->offset(C('offset'))->order("`order` desc,parentid asc")->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["parentname"] = ($val["parentid"] == 0)?'/':$party[$val['parentid']];
			}
		}
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		return $list;
		
    }
	
	/*
	  @获取指定部门的所有子部门id
	  @parentid
	  @newChilds
	  @hasSelf 是否包含自身
	*/
	public function getChildId($parentid,$newChilds = true,$hasSelf = true){
		if($parentid > 0){
			if($newChilds == true){
				$this->ChildId = array(); 
				if($hasSelf == true) $this->ChildId[] = $parentid;
			}
			$list = $this->model->table('party')->field("partyid")->where("parentid=$parentid and isdelete=0")->select();
			if($list){
				foreach($list as $k=>$val){
					$this->ChildId[] = $val["partyid"];
					$this->getChildId($val["partyid"],false);
				}
			}
			return $this->ChildId;
		}
	}
	
    public function save() {
		if(requestInt("partyid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
    }
	
	public function create(){
		if(request("name") != ''){
			$partyid = $this->model->table("party")->insert($_POST);
			$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
			if($partyid){
				echo success("添加成功");
			}else{
				echo fail("添加失败");
			}
		}
	}
	
	public function modify(){
		if(requestInt("partyid") > 0){
			$partyid = requestInt("partyid");
			$result = $this->model->table("party")->where("partyid",$partyid)->update($_POST);
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			if($result){
				echo success("编辑成功");
			}else{
				echo fail("编辑失败");
			}
		}
	}
	
    public function remove() {
		if(requestInt("partyid")>0){
			$partyid = requestInt("partyid");
			
			$names = $this->model->table("party")
			->field('name')->where("parentid",$partyid)
			->where("isdelete=0")
			->toList('name');
			if($names){
				$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
				exit(fail(array('errmsg'=>L("msg.remove_party",implode("、",$names)))));
			}
			
			$result = $this->model->table("party")->where("partyid",$partyid)->update("isdelete=1");
			
			$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();
			
			if($result){
				echo success(array('errmsg'=>"删除成功"));
			}else{
				echo fail(array('errmsg'=>"删除失败"));
			}
		}
    }
	
    public function getRow() {
		if(requestInt("partyid") > 0){
			$partyid = requestInt("partyid");
			$row = $this->model->table("party")->field()->where("partyid",$partyid)->find();
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			return $row;
		}	
    }
	
	public function getParty(){
        $list = $this->model->table("party")
		->field("partyid,parentid,name")
		->where("isdelete=0")
		>order("`order` asc")
		->select();
		$params = array('partyid','parentid','name','name');
		$category = $this->librarys("Category",array('config'=>$params));
		return $category->getTree($list);
	}
	
    public function getPartyJson() {
		$where = array();
		if(session("roleid")>2){
			$partyid = session("partyid");
			$where[] = "partyid=$partyid";
		}
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('party')
		->field("partyid as id,name,parentid as pId")
		->where($where)
		->order('`order` desc,partyid asc')->select();
        foreach ($list as $k => $val) {
            $list[$k]["name"] = $val["name"];
            $list[$k]["title"] = $val["name"] . "|" . $val["id"];
            $list[$k]["open"] = true;
			$list[$k]["partyid"] = $val["id"];
        }
		array_unshift($list,array('id'=>0,'partyid'=>0,'name'=>L('base.All'),'title'=>L('base.All'),'open'=>true));
        return json_encode($list);
    }

    public function getJson(){
		$where = array();
		if(session("roleid")>2){
			$partyid = session("partyid");
			$partyids = $this->getChildId($partyid);
			$where[] = "partyid in (".implode(",",$partyids).")";
		}
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('party')
		->field("partyid,name,parentid as pId")
		->where($where)
		->order('`order` desc,partyid asc')
		->select();
        foreach ($list as $k => $val) {
			$list[$k]["id"] = $val["partyid"];
            $list[$k]["title"] = $val["name"];
            $list[$k]["open"] = true;
        }
        return json_encode($list);
    }

    public function getPartyAll(){
		$where = array();
		$partyid = 0;
		if(session("roleid") > 2){
			$partyid = 1;
			if(session("masterPartyid")){
				$partyids = $this->getChildId(session("masterPartyid"));
				$where[]="partyid in (".implode(",",$partyids).")";
			}else{
				$partyids = $this->getChildId(session("partyid"));
				$where[]="partyid in (".implode(",",$partyids).")";
			}
		}
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('party')
		->field("partyid as id,name,parentid as pId")
		->where($where)
		->order('`order` desc,partyid asc')
		->select();
		
        $cat = $this->librarys("Category", array("config" => array('id','pId','name','name')));
        $list = $cat->getTree($list,$partyid);
		return $list;
    }

    public function partyUser($partyid = 0,$userids = array(),$self = true,$chkDisabled = true){
        if ($partyid > 0) {
			
			$where = array();
			$partyids = $this->getChildId($partyid);
			$where[] = "partyid in (".implode(",",$partyids).")";
			$where[] = "isdelete=0";
			$where = implode(" and ",$where);
            $list = $this->model->table("party")->field('partyid as id,name,parentid as pId')->where($where)->order("`order` desc")->select();
        } else {
            $list = $this->model->table("party")->field('partyid as id,name as name,parentid as pId')->where("isdelete=0")->order("`order` desc")->select();
        }
        foreach ($list as $k => $val) {
            if ($val['pId'] == 0) {
                $list[$k]["open"] = TRUE;
            } else {
                $list[$k]["open"] = FALSE;
				if($partyid > 0){
					$list[$k]["open"] = TRUE;
				}
            }
            $list[$k]["title"] = $val["name"];
            if($userids){
				if(session("roleid")>2 && $self == false){
					foreach($userids as $k=>$userid){
						if(session("userid") == $userid){
							unset($userids[$k]);
						}
					}
				}
                $children = $this->model->table("user")
				->field("userid,name,weixinid,status")
				->where("partyid", $val["id"])
				->where("in",array('userid'=>$userids))
				->where("isdelete=0")
				->select();
            }else{
				if(session("roleid")>2 && $self == false){
					$children = $this->model->table("user")
					->field("userid,name,weixinid,mobile,status")
					->where("partyid", $val["id"])
					->where("userid",session("userid"),"<>")
					->where("isdelete=0")
					->select();
				}else{
					$children = $this->model->table("user")
					->field("userid,name,weixinid,mobile,status")
					->where("partyid", $val["id"])
					->where("isdelete=0")
					->select();
				}
            }
            foreach ($children as $kk => $rs) {
                $children[$kk]["title"] = $rs["name"];
				if($rs["status"] == 4){
					$children[$kk]["name"] .= '(未关注)';
				}
				$children[$kk]["icon"] = "__PUBLIC__/plugins/ztree/img/user.png";
				if($chkDisabled == true){
					if($rs["status"] == 4){
						$children[$kk]["chkDisabled"] = true;
					}
				}
            }
            $list[$k]["children"] = $children;
        }
        return json_encode($list);
    }

    public function partyUserNoJson($partyid = 0){
        return $this->model->table("user")->field("userid,name")->where("partyid", $partyid)->select();
    }

	
}

?>
