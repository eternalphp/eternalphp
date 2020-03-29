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

class statisticsModel extends BaseModel{
	
    public function __construct() {
        parent::__construct();
    }
	
/* 	制定年度工作计划
	党员大会/每季一次
	支委会/每月一次
	党小组会/每月一次
	党课教育/每季一次
	专题组织生活会/每年一次
	党员参加轮训/每年一次
	党员接受电化教育/每两月一次
	民主评议党员/每年 */
	
    public function index() {
		$year = request("year",date("Y"));
		$season = ceil((date('n'))/3); //当前季度
		if($year<date("Y")){
			$season = 4;
		}
		
		$where = array();
		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = "name like '%".$keyword."%'";
		}
		
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
		
		$where[] = "parentid>0 and isdelete=0";
		$where = implode(" and ",$where);
		
		$list = $this->model->table("party")
		->field()
		->where($where)
		->offset(C("offset"))
		->order("`order` desc")
		->select();
		if($list){
			foreach($list as $k=>$val){
				
				$hasChild = M("party")->where("parentid",$val['partyid'])->find();
				if($hasChild){
					unset($list[$k]);
					continue;
				}
				
				//年度工作计划
				$total = M("plan")
				->where("partyid",$val["partyid"])
				->where("year=$year")
				->where("isdelete=0")
				->count("planid")
				->getVal("total");
				$list[$k]["plan"] = 0;
				if($total>=1) $list[$k]["plan"] = 1;
				
				//党员大会
				$list[$k]["meeting"][1] = $this->getMeetingCount($val["partyid"],1,$year);
				$list[$k]["meeting"][2] = $this->getMeetingCount($val["partyid"],2,$year);
				$list[$k]["meeting"][3] = $this->getMeetingCount($val["partyid"],3,$year);
				$list[$k]["meeting"][4] = $this->getMeetingCount($val["partyid"],4,$year);
				$list[$k]["meeting"][5] = $this->getMeetingCount($val["partyid"],5,$year);
				$list[$k]["meeting"][6] = $this->getMeetingCount($val["partyid"],6,$year);
				
				//党课教育 每季度一次 每个季度判断一次是否完成
				/******************************
				$complete = 0;
				for($num=1;$num<=$season;$num++){
					$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
					$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
					$total = M("education")
					->where("partyid",$val["partyid"])
					->where("startdate between '$starttime' and '$endtime'")
					->where("isdelete=0")
					->count("eduid")
					->getVal("total");
					if($total>=1){
						$complete++;
					}
				}
				*****************************************/
				
				$list[$k]["education"] = $this->getMeetingCount($val["partyid"],9,$year);
				
				
				//民主评议党员 每年一次
				$total = M("evaluate")
				->where("partyid",$val["partyid"])
				->where("YEAR(startdate)=$year")
				->where("isdelete=0")
				->count("evalid")
				->getVal("total");
				$list[$k]["evaluate"] = 0;
				if($total>=1) $list[$k]["evaluate"] = 1;
			}
		}
		return $list;
    }
	
	public function getMeetingCount1($partyid,$typeid = 1,$year){
		if($typeid>0 && $partyid>0 && $year>0){
			return M("meeting")
			->where("partyid=$partyid")
			->where("typeid=$typeid")
			->where("YEAR(startdate)=$year")
			->where("isdelete=0")
			->count("meetingid")
			->getVal("total");
		}
	}
	
	//指定部门某个年份完成情况
	public function getMeetingCount($partyid,$typeid = 1,$year){
		if($typeid>0 && $partyid>0 && $year>0){
			
			$season = ceil((date('n'))/3); //当前季度
			$month = date("n");
			if($year<date("Y")){
				$season = 4;
				$month = 12;
			}
			
			switch($typeid){
				case 1:
				case 9:
					$complete = 0; //完成次数
					$totalCount = 0;
					for($num=1;$num<=$season;$num++){
						$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
						$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
						$total = $this->getMeetingCounts($partyid,$typeid,$starttime,$endtime);
						$totalCount += $total;
						if($total>=1){
							$complete++;
						}
					}
					return $totalCount;
				
				break;
				case 2:
				case 3:
					$complete = 0; //完成次数
					$totalCount = 0;
					for($num=1;$num<=$month;$num++){
						$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num,1,$year));
						$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +1 month -1 day"));
						$total = $this->getMeetingCounts($partyid,$typeid,$starttime,$endtime);
						$totalCount += $total;
						if($total>=1){
							$complete++;
						}
					}
					return $totalCount;
				break;
				case 4:
				case 5:
					$complete = 0;
					$totalCount = 0;
					$total = M("meeting")
					->where("partyid",$partyid)
					->where("typeid=$typeid")
					->where("YEAR(startdate)=$year")
					->where("isdelete=0")
					->count("meetingid")
					->getVal("total");
					$totalCount += $total;
					if($total>=1) $complete = 1;
					return $totalCount;
				break;
				case 6:
					$complete = 0; //完成次数
					$totalCount = 0;
					//每两月一次
					for($num=1;$num<=ceil($month/2);$num++){
						$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*2-2+1,1,$year));
						$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +2 month -1 day"));
						$total = $this->getMeetingCounts($partyid,$typeid,$starttime,$endtime);
						$totalCount += $total;
						if($total>=1){
							$complete++;
						}
					}
					return $totalCount;
				break;
			}
		}
	}
	
	//指定部门某个时间段完成次数
	function getMeetingCounts($partyid,$typeid,$starttime,$endtime){
		return M("meeting")
		->where("partyid=$partyid")
		->where("typeid=$typeid")
		->where("startdate between '$starttime' and '$endtime'")
		->where("isdelete=0")
		->count("meetingid")
		->getVal("total");
	}
	
	//参加活动次数
	public function activitys(){
		$year = request("year",date("Y"));
		$where = array();
		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = "(t1.name like '%".$keyword."%' or t1.mobile like '%".$keyword."%' or t2.name like '%".$keyword."%')";
		}
		
		if(session("masterPartyid")){
			$partyids = $this->models("party")->getChildId(session("masterPartyid"),true,false);
			if($partyids){
				$where[] = "t1.partyid in (".implode(",",$partyids).")";
			}
		}else{
			$partyids = $this->models("party")->getChildId(session("partyid"),true,false);
			if($partyids){
				$where[] = "t1.partyid in (".implode(",",$partyids).")";
			}
		}
		
		$where[] = "t1.isdelete=0 and t2.isdelete=0";
		$where = implode(" and ",$where);
		$list = $this->model->table("user as t1")
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as partyName")
		->where($where)
		->offset(C("offset"))
		->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["meeting"][1] = $this->getUserMeetingCount($val["userid"],1,$year);
				$list[$k]["meeting"][2] = $this->getUserMeetingCount($val["userid"],2,$year);
				$list[$k]["meeting"][3] = $this->getUserMeetingCount($val["userid"],3,$year);
				$list[$k]["meeting"][4] = $this->getUserMeetingCount($val["userid"],4,$year);
				$list[$k]["meeting"][5] = $this->getUserMeetingCount($val["userid"],5,$year);
				$list[$k]["meeting"][6] = $this->getUserMeetingCount($val["userid"],6,$year);
				$list[$k]["meeting"][8] = $this->getUserMeetingCount($val["userid"],8,$year);//入党积极分子培训
				$list[$k]["meeting"][9] =  $this->getUserMeetingCount($val["userid"],9,$year);//党课教育
				
				/*********************************************************
				$list[$k]["education"] = M("education as t1")
				->join("education_user as t2","t1.eduid=t2.eduid")
				->where("t2.userid",$val["userid"])
				->where("YEAR(t1.startdate)=$year")
				->where("t1.isdelete=0")
				->where("t2.status=1")
				->count("t2.id")
				->getVal("total");
				*******************************************************/
				
				//民主评议党员
				$list[$k]["evaluate"] = M("evaluate as t1")
				->join("evaluate_user as t2","t1.evalid=t2.evalid")
				->where("t2.userid",$val["userid"])
				->where("YEAR(t1.startdate)=$year")
				->where("t1.isdelete=0")
				->where("t2.status<>1")
				->count("t2.id")
				->getVal("total");
			}
		}
		return $list;
	}
	
	function getUserMeetingCount($userid,$typeid = 1,$year){
		if($userid>0){
			return M("meeting as t1")
			->join("meeting_user as t2","t1.meetingid=t2.meetingid")
			->where("t2.userid=$userid")
			->where("t1.typeid=$typeid")
			->where("YEAR(t1.startdate)=$year")
			->where("t1.isdelete=0")
			->where("t2.status=1")
			->count("t2.id")
			->getVal("total");
		}
	}
	
	//谈心谈话统计
	public function talks(){
		$year = request("year",date("Y"));
		$where = array();
		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = "name like '%".$keyword."%'";
		}
		$where[] = "parentid>0 and isdelete=0";
		$where = implode(" and ",$where);
		$list = $this->model->table("party")
		->field()
		->where($where)
		->offset(C("offset"))
		->order("`order` desc")
		->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["total"] = M("talk")
				->where("partyid",$val["partyid"])
				->where("YEAR(startdate)=$year")
				->where("isdelete=0")
				->count("talkid")
				->getVal("total");
			}
		}
		return $list;
	}
	
	/**************************************************************************************/
	
	//政治面貌
	
	//性别
	
	//年龄
	
	//文化程度
	
	//所有成员
	function getPolitical(){
		
		$where = array();
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
		$list = M("political")->select();
		if($list){
			foreach($list as $k=>$val){
				
				$list[$k]["value"] = M("user")->where("politicalid",$val["politicalid"])->where($where)->count("userid")->getVal("total");
			}
		}
		return $list;
	}
	
	//在职党员
	function getGender(){
		
		$where = array();
		$where[] = "isdelete=0";
		$where[] = "isPartyMember=1";
		$where = implode(" and ",$where);
		
		return M("user")->field("COUNT(gender) as total,gender")->where($where)->group("gender")->order("total desc")->toList("gender","total");
	}
	
	//在职党员
	function getAge(){
		$data = array(
			20=>array('name'=>'20-30岁','value'=>0),
			30=>array('name'=>'30-40岁','value'=>0),
			40=>array('name'=>'40-50岁','value'=>0),
			50=>array('name'=>'50-60岁','value'=>0),
			60=>array('name'=>'60岁以上','value'=>0),
		);
		
		$where = array();
		$where[] = "isdelete=0";
		$where[] = "isPartyMember=1";
		$where = implode(" and ",$where);
		
		$list = M("user")->field("name,TIMESTAMPDIFF(YEAR,bothday,DATE(now())) as age")->where($where)->where("bothday is not null")->select();
		if($list){
			foreach($list as $k=>$val){
				if($val["age"]>20 && $val["age"]<=30){
					$data[20]["value"]++;
				}elseif($val["age"]>30 && $val["age"]<=40){
					$data[30]["value"]++;
				}elseif($val["age"]>40 && $val["age"]<=50){
					$data[40]["value"]++;
				}elseif($val["age"]>50 && $val["age"]<=60){
					$data[50]["value"]++;
				}elseif($val["age"]>60){
					$data[60]["value"]++;
				}
			}
		}
		return array_values($data);
	}
	
	//在职党员
	function getEducation(){
		$where = array();
		$where[] = "isdelete=0";
		$where[] = "isPartyMember=1";
		
		$where = implode(" and ",$where);
		
		$list = M("educations")->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["value"] = M("user")->where("eduid",$val["eduid"])->where($where)->count("userid")->getVal("total");
				unset($list[$k]["eduid"]);
			}
		}
		return $list;
	}
	
	function getPartyUsers(){
		$where = array();
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
		return M("user")->where($where)->count("userid")->getVal("total");
	}
}
?>
