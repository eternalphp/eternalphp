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

class scoreModel extends BaseModel{
	
    public function __construct() {
        parent::__construct();
    }
	
    public function index() {
        $where = array();
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "(t2.name like '%" . $keyword . "%' or t3.name like '%" . $keyword . "%')";
        }
		
		if(session("roleid")>2){
			if(session("masterPartyid")){
				$partyids = $this->models("party")->getChildId(session("masterPartyid"));
				if($partyids){
					$where[] = "t1.partyid in (".implode(",",$partyids).")";
				}
			}else{
				$partyids = $this->models("party")->getChildId(session("partyid"));
				if($partyids){
					$where[] = "t1.partyid in (".implode(",",$partyids).")";
				}
			}
		}
		
		$where[] = "t2.isdelete=0";
		$where = implode(" and ",$where);
		
        $list = $this->model->table('score as t1')
		->join("user as t2","t1.userid=t2.userid")
		->join("party as t3","t1.partyid=t3.partyid")
		->field("t1.*,t2.name,t3.name as party_name")
		->where($where)
		->offset(C("offset"))
		->order("t1.createtime desc")
		->select();
		
		if($list){
			foreach($list as $k=>$val){
				$res = $this->table("score_rules as t1")
				->join("operation as t2","t1.optid=t2.optid")
				->field("t1.title,t2.name")
				->where("t1.itemid",$val["itemid"])
				->find();
				if($res){
					$list[$k]["reason"] = $res["title"]." - ".$res["name"];
				}
			}
		}
        return $list;
    }
	
    public function member() {
        $where = array();
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "(t1.name like '%" . $keyword . "%' or t2.name like '%" . $keyword . "%')";
        }
		
		if(session("roleid")>2){
			if(session("masterPartyid")){
				$partyids = $this->models("party")->getChildId(session("masterPartyid"));
				if($partyids){
					$where[] = "t1.partyid in (".implode(",",$partyids).")";
				}
			}else{
				$partyids = $this->models("party")->getChildId(session("partyid"));
				if($partyids){
					$where[] = "t1.partyid in (".implode(",",$partyids).")";
				}
			}
		}
		
		
		$where[] = "t2.isdelete=0";
		$where = implode(" and ",$where);
		
        $list = $this->model->table('user as t1')
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as party_name")
		->where($where)
		->offset(C("offset"))
		->order("t1.score desc")
		->select();
        return $list;
    }
	
    public function party() {
        $where = array();
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "(name like '%" . $keyword . "%')";
        }
		
		if(session("roleid")>2){
			if(session("masterPartyid")){
				$partyids = $this->models("party")->getChildId(session("masterPartyid"),true,false);
				if($partyids){
					$where[] = "partyid in (".implode(",",$partyids).")";
				}
			}else{
				$partyids = $this->models("party")->getChildId(session("partyid"),true,false);
				if($partyids){
					$where[] = "partyid in (".implode(",",$partyids).")";
				}
			}
		}
		
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
		
        $list = $this->model->table('party')
		->field()
		->where($where)
		->offset(C("offset"))
		->order("`order` desc")
		->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["users"] = $this->table("user")->where("partyid",$val["partyid"])->where("isdelete=0")->count('userid')->getVal("total");
				$list[$k]["score"] = $this->table("user")->where("partyid",$val["partyid"])->where("isdelete=0")->sum('score')->getVal("score");
				$list[$k]["avg"] = $this->table("user")->where("partyid",$val["partyid"])->where("isdelete=0")->avg('score')->getVal("score");
				$list[$k]["avg"] = round($list[$k]["avg"],2);
			}
		}
        return $list;
    }
	
	public function getScoreRules(){
		return $this->table("score_rules as t1")
		->join("operation as t2","t1.optid=t2.optid")
		->field("t1.*,t2.name")
		->select();
	}
	
	public function save(){
		if($_POST["items"]){
			foreach($_POST["items"] as $itemid=>$score){
				$this->model->table("score_rules")->where("itemid=$itemid")->update(array(
					'score'=>$score
				));
			}
			echo success("提交成功");
		}
	}
}
?>
