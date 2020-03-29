<?php

namespace App\Home\Models;

class statisticsModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index(){

	}
	
	public function talk(){
		$year = request("year",date("Y"));
		if(session("adminGroup") == self::groupBranch){
			$list = M("user")->where("partyid",session("partyid"))->where("isdelete=0")->select();
			if($list){
				foreach($list as $k=>$val){
					$total = M("talk")->where("userid",$val["userid"])
					->where("YEAR(startdate)=$year")
					->where("isdelete=0")
					->count("talkid")
					->getVal("total");
					$list[$k]["total"] = $total;
					$list[$k]["percent"] = ($total>0)?($total/10)*100:0;
				}
			}
		}else{
			$where = array();
			if(session("adminGroup") == self::groupParty){
				$where[] = "parentid=".session("partyid");
			}
			$where[] = "parentid>0 and isdelete=0";
			$where = implode(" and ",$where);
			$list = M("party")->where($where)->order("`order` desc")->select();
			if($list){
				foreach($list as $k=>$val){
					$total = M("talk")->where("partyid",$val["partyid"])
					->where("YEAR(startdate)=$year")
					->where("isdelete=0")
					->count("talkid")
					->getVal("total");
					$list[$k]["total"] = $total;
					$list[$k]["percent"] = ($total>0)?($total/10)*100:0;
				}
			}
		}
		return $list;
	}
	
	//学习园地
	public function study(){
		$year = request("year",date("Y"));
		
		if(session("adminGroup") == self::groupBranch){
			$list = M("user")->where("partyid",session("partyid"))->where("isdelete=0")->select();
			if($list){
				foreach($list as $k=>$val){
					$total = M("news")->where("userid",$val["userid"])
					->where("YEAR(createtime)=$year")
					->where("isdelete=0")
					->count("newsid")
					->getVal("total");
					$list[$k]["total"] = $total;
					$list[$k]["percent"] = ($total>0)?($total/10)*100:0;
				}
			}
		}else{
		
			$where = array();
			$where[] = "parentid>0";
			
			/**********************************************
			if(session("identityid") == 3){
				$partyids = $this->getChildId(session("partyid"));
				if($partyids){
					$where[] = "partyid in (".implode(",",$partyids).")";
				}
			}
			************************************************/
			
			if(session("adminGroup") == self::groupParty){
				$where[] = "parentid=".session("partyid");
			}
			
			$where[] = "isdelete=0";
			$where = implode(" and ",$where);
			$list = M("party")->where($where)->order("`order` desc")->select();
			if($list){
				foreach($list as $k=>$val){
					$total = M("news")->where("partyid",$val["partyid"])
					->where("YEAR(createtime)=$year")
					->where("isdelete=0")
					->count("newsid")
					->getVal("total");
					$list[$k]["total"] = $total;
					$list[$k]["percent"] = ($total>0)?($total/10)*100:0;
				}
			}
		}
		return $list;
	}
	
	
	public function plan(){
		$year = request("year",date("Y"));

		$totalComplete = 0; //达标次数
		$totalNotCompleted = 0; //未达标次数
		
		$where = array();
		if(session("adminGroup") == self::groupParty){
			$where[] = "parentid=".session("partyid");
		}elseif(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}
		$where[] = "parentid>0 and isdelete=0";
		$where = implode(" and ",$where);
		
		$list = M("party")->where($where)->order("`order` desc")->select();
		if($list){
			foreach($list as $k=>$val){
				
				$total = M("plan")
				->where("partyid",$val["partyid"])
				->where("year=$year")
				->where("isdelete=0")
				->count("planid")
				->getVal("total");
				if($total>=1){
					$list[$k]["complete"] = true;
					$complete = 1;
					$notCompleted = 0;
				}else{
					$list[$k]["complete"] = false;
					$notCompleted = 1;
					$complete = 0;
				}
				
				$totalComplete += $complete;
				$totalNotCompleted += $notCompleted;
				
				$list[$k]["total"] = $complete;
				$list[$k]["notCompleted"] = $notCompleted;
				$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
			}
		}
		
		return array(
			'list'=>$list,
			'totalComplete'=>$totalComplete,
			'totalNotCompleted'=>$totalNotCompleted
		);
	}
	
	/*****************
	党员大会/每季一次
	支委会/每月一次
	党小组会/每月一次
	党课教育/每季一次
	专题组织生活会/每年一次
	党员参加轮训/每年一次
	党员接受电化教育/每两月一次
	民主评议党员/每年一次
	*****************/
	public function meeting(){
		if(session("adminGroup") == self::groupBranch){
			return $this->meetingUsers();
		}else{
			
			$year = request("year",date("Y"));
			$typeid = request("typeid",1);
			
			$season = ceil((date('n'))/3); //当前季度
			$month = date("n");
			if($year<date("Y")){
				$season = 4;
				$month = 12;
			}
			
			$totalComplete = 0; //达标次数
			$totalNotCompleted = 0; //未达标次数
		
			$where = array();
			if(session("adminGroup") == self::groupParty){
				$where[] = "parentid=".session("partyid");
			}
			$where[] = "parentid>0 and isdelete=0";
			$where = implode(" and ",$where);
			
			$list = M("party")->where($where)->order("`order` desc")->select();
			if($list){
				foreach($list as $k=>$val){
					
					$hasChild = M("party")->where("parentid",$val['partyid'])->find();
					if($hasChild){
						unset($list[$k]);
						continue;
					}
					
					switch($typeid){
						case 1:
						case 9:
							$complete = 0; //完成次数
							$notCompleted = 0; //未完成次数
							$totalCount = 0;
							
							for($num=1;$num<=$season;$num++){
								$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
								$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
								$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
								$totalCount += $total;
								if($total>=1){
									$complete++;
								}else{
									if($year == date("Y")){
										if($num<$season) $notCompleted++;
									}else{
										$notCompleted++;
									}
								}
							}
							
							$list[$k]["total"] = $totalCount;
							$list[$k]["notCompleted"] = $notCompleted;
							$list[$k]["percent"] = ($totalCount>0)?($totalCount/10)*100:0;
						
						break;
						case 2:
						case 3:
							$complete = 0; //完成次数
							$notCompleted = 0; //未完成次数
							$totalCount = 0;
							
							for($num=1;$num<=$month;$num++){
								$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num,1,$year));
								$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +1 month -1 day"));
								$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
								$totalCount += $total;
								if($total>=1){
									$complete++;
								}else{
									if($year == date("Y")){
										if($num<$month) $notCompleted++;
									}else{
										$notCompleted++;
									}
								}
							}
							
							$list[$k]["total"] = $totalCount;
							$list[$k]["notCompleted"] = $notCompleted;
							$list[$k]["percent"] = ($totalCount>0)?($totalCount/10)*100:0;
						break;
						case 4:
						case 5:
							$total = M("meeting")
							->where("partyid",$val["partyid"])
							->where("typeid=$typeid")
							->where("YEAR(startdate)=$year")
							->where("isdelete=0")
							->count("meetingid")
							->getVal("total");
							if($total>=1){
								$list[$k]["complete"] = true;
								$complete = 1;
								$notCompleted = 0;
							}else{
								$list[$k]["complete"] = false;
								$notCompleted = 1;
								$complete = 0;
							}
							
						break;
						case 6:
							$complete = 0; //完成次数
							$notCompleted = 0; //未完成次数
							$totalCount = 0;
							//每两月一次
							for($num=1;$num<=ceil($month/2);$num++){
								$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*2-2+1,1,$year));
								$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +2 month -1 day"));
								$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
								$totalCount += $total;
								if($total>=1){
									$complete++;
								}else{
									if($year == date("Y")){
										if($num<ceil($month/2)) $notCompleted++;
									}else{
										$notCompleted++;
									}
								}
							}
							
							$list[$k]["total"] = $totalCount;
							$list[$k]["notCompleted"] = $notCompleted;
							$list[$k]["percent"] = ($totalCount>0)?($totalCount/10)*100:0;
						break;
						case 8:
							$complete = 0; //完成次数
							$notCompleted = 0; //未完成次数
							$totalCount = 0;
							for($num=1;$num<=$season;$num++){
								$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
								$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
								$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
								$totalCount += $total;
								if($total>=1){
									$complete++;
								}else{
									if($year == date("Y")){
										if($num<$season) $notCompleted++;
									}else{
										$notCompleted++;
									}
								}
							}
							
							$totalComplete += $complete;
							$totalNotCompleted += $notCompleted;
							
							$list[$k]["total"] = $totalCount;
							$list[$k]["notCompleted"] = $notCompleted;
							$list[$k]["percent"] = ($totalCount>0)?($totalCount/10)*100:0;
							
						break;
					}
					
					$totalComplete += $complete;
					$totalNotCompleted += $notCompleted;
				}
			}
			$maxNum = 10;
			if($list){
				foreach($list as $k=>$val){
					if($val['total']>$maxNum){
						$maxNum = $val['total'];
					}
				}
			}
			if($maxNum > 10){
				$maxNum = ceil($maxNum/10)*10;
			}
			
			if($list){
				foreach($list as $k=>$val){
					if(isset($val['percent'])){
						$list[$k]["percent"] = ($val['total']>0)?($val['total']/$maxNum)*100:0;
					}
				}
			}
			
			return array(
				'list'=>$list,
				'maxNum'=>$maxNum,
				'totalComplete'=>$totalComplete,
				'totalNotCompleted'=>$totalNotCompleted
			);
		}
	}
	
	//仅查看本部门下的成员
	public function meetingUsers(){
		$year = request("year",date("Y"));
		$typeid = request("typeid",1);
		
		$season = ceil((date('n'))/3); //当前季度
		$month = date("n");
		if($year<date("Y")){
			$season = 4;
			$month = 12;
		}
		
		$totalComplete = 0; //达标次数
		$totalNotCompleted = 0; //未达标次数
		
		$list = M("user")->where("partyid",session("partyid"))->where("isdelete=0")->select();
		if($list){
			foreach($list as $k=>$val){
				
				switch($typeid){
					case 1:
					case 9:
						$complete = 0; //完成次数
						$notCompleted = 0; //未完成次数
						
						for($num=1;$num<=$season;$num++){
							$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
							$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
							$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
							if($total>=1){
								$complete++;
							}else{
								if($year == date("Y")){
									if($num<$season) $notCompleted++;
								}else{
									$notCompleted++;
								}
							}
						}
						
						$list[$k]["total"] = $complete;
						$list[$k]["notCompleted"] = $notCompleted;
						$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
					
					break;
					case 2:
					case 3:
						$complete = 0; //完成次数
						$notCompleted = 0; //未完成次数
						
						for($num=1;$num<=$month;$num++){
							$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num,1,$year));
							$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +1 month -1 day"));
							$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
							if($total>=1){
								$complete++;
							}else{
								if($year == date("Y")){
									if($num<$month) $notCompleted++;
								}else{
									$notCompleted++;
								}
							}
						}
						
						$list[$k]["total"] = $complete;
						$list[$k]["notCompleted"] = $notCompleted;
						$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
					break;
					case 4:
					case 5:
						$total = M("meeting")
						->where("partyid",$val["partyid"])
						->where("typeid=$typeid")
						->where("YEAR(startdate)=$year")
						->where("isdelete=0")
						->count("meetingid")
						->getVal("total");
						if($total>=1){
							$list[$k]["complete"] = true;
							$complete = 1;
							$notCompleted = 0;
						}else{
							$list[$k]["complete"] = false;
							$notCompleted = 1;
							$complete = 0;
						}
						
					break;
					case 6:
						$complete = 0; //完成次数
						$notCompleted = 0; //未完成次数
						
						//每两月一次
						for($num=1;$num<=ceil($month/2);$num++){
							$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*2-2+1,1,$year));
							$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +2 month -1 day"));
							$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
							if($total>=1){
								$complete++;
							}else{
								if($year == date("Y")){
									if($num<ceil($month/2)) $notCompleted++;
								}else{
									$notCompleted++;
								}
							}
						}
						
						$list[$k]["total"] = $complete;
						$list[$k]["notCompleted"] = $notCompleted;
						$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
					break;
					case 8:
						$complete = 0; //完成次数
						$notCompleted = 0; //未完成次数
						
						for($num=1;$num<=$season;$num++){
							$starttime = date('Y-m-d H:i:s', mktime(0, 0, 0,$num*3-3+1,1,$year));
							$endtime = date("Y-m-d 23:59:59",strtotime("$starttime +3 month -1 day"));
							$total = $this->getMeetingCount($val["partyid"],$typeid,$starttime,$endtime);
							if($total>=1){
								$complete++;
							}else{
								if($year == date("Y")){
									if($num<$season) $notCompleted++;
								}else{
									$notCompleted++;
								}
							}
						}
						
						$totalComplete += $complete;
						$totalNotCompleted += $notCompleted;
						
						$list[$k]["total"] = $complete;
						$list[$k]["notCompleted"] = $notCompleted;
						$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
						
					break;
				}
				
				$totalComplete += $complete;
				$totalNotCompleted += $notCompleted;
			}
		}
		return array(
			'list'=>$list,
			'totalComplete'=>$totalComplete,
			'totalNotCompleted'=>$totalNotCompleted
		);
	}
	
	// 党课教育 取消
	public function education(){
		$year = request("year",date("Y"));
		$season = ceil((date('n'))/3); //当前季度
		if($year<date("Y")){
			$season = 4;
		}
		
		$totalComplete = 0; //达标次数
		$totalNotCompleted = 0; //未达标次数
		
		$list = M("party")->where("parentid>0 and isdelete=0")->order("`order` desc")->select();
		if($list){
			foreach($list as $k=>$val){
				$complete = 0; //完成次数
				$notCompleted = 0; //未完成次数
				
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
					}else{
						if($year == date("Y")){
							if($num<$season) $notCompleted++;
						}else{
							$notCompleted++;
						}
					}
				}
				
				$totalComplete += $complete;
				$totalNotCompleted += $notCompleted;
				
				$list[$k]["total"] = $complete;
				$list[$k]["notCompleted"] = $notCompleted;
				$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
			}
		}
		return array(
			'list'=>$list,
			'totalComplete'=>$totalComplete,
			'totalNotCompleted'=>$totalNotCompleted
		);		
	}
	
	public function evaluate(){
		$year = request("year",date("Y"));

		$totalComplete = 0; //达标次数
		$totalNotCompleted = 0; //未达标次数
		
		if(session("adminGroup") == self::groupBranch){
			$list = M("user")->where("partyid",session("partyid"))->where("isdelete=0")->select();
			if($list){
				foreach($list as $k=>$val){
					
					$total = M("evaluate")
					->where("userid",$val["userid"])
					->where("YEAR(startdate)=$year")
					->where("isdelete=0")
					->count("evalid")
					->getVal("total");
					if($total>=1){
						$list[$k]["complete"] = true;
						$complete = 1;
						$notCompleted = 0;
					}else{
						$list[$k]["complete"] = false;
						$notCompleted = 1;
						$complete = 0;
					}
					
					$totalComplete += $complete;
					$totalNotCompleted += $notCompleted;
					
					$list[$k]["total"] = $complete;
					$list[$k]["notCompleted"] = $notCompleted;
					$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
				}
			}
		}else{
			$where = array();
			if(session("adminGroup") == self::groupParty){
				$where[] = "parentid=".session("partyid");
			}
			$where[] = "parentid>0 and isdelete=0";
			$where = implode(" and ",$where);
			
			$list = M("party")->where($where)->order("`order` desc")->select();
			if($list){
				foreach($list as $k=>$val){
					
					$total = M("evaluate")
					->where("partyid",$val["partyid"])
					->where("YEAR(startdate)=$year")
					->where("isdelete=0")
					->count("evalid")
					->getVal("total");
					if($total>=1){
						$list[$k]["complete"] = true;
						$complete = 1;
						$notCompleted = 0;
					}else{
						$list[$k]["complete"] = false;
						$notCompleted = 1;
						$complete = 0;
					}
					
					$totalComplete += $complete;
					$totalNotCompleted += $notCompleted;
					
					$list[$k]["total"] = $complete;
					$list[$k]["notCompleted"] = $notCompleted;
					$list[$k]["percent"] = ($complete>0)?($complete/10)*100:0;
				}
			}
		}
		return array(
			'list'=>$list,
			'totalComplete'=>$totalComplete,
			'totalNotCompleted'=>$totalNotCompleted
		);
	}
	
	function getMeetingCount($partyid,$typeid,$starttime,$endtime){
		return M("meeting")
		->where("partyid=$partyid")
		->where("typeid=$typeid")
		->where("startdate between '$starttime' and '$endtime'")
		->where("isdelete=0")
		->count("meetingid")
		->getVal("total");
	}
	
	
	/**************************************************************************************/
	
	//政治面貌
	
	//性别
	
	//年龄
	
	//文化程度
	
	function getPolitical(){
		
		$where = array();
		if(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}elseif(session("adminGroup") == self::groupParty){
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
		}
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
	
	function getGender(){
		
		$where = array();
		if(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}elseif(session("adminGroup") == self::groupParty){
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
		}
		$where[] = "isdelete=0";
		$where[] = "isPartyMember=1";
		$where = implode(" and ",$where);
		
		return M("user")->field("COUNT(gender) as total,gender")->where($where)->group("gender")->order("total desc")->toList("gender","total");
	}
	
	function getAge(){
		$data = array(
			20=>array('name'=>'20-30岁','value'=>0),
			30=>array('name'=>'30-40岁','value'=>0),
			40=>array('name'=>'40-50岁','value'=>0),
			50=>array('name'=>'50-60岁','value'=>0),
			60=>array('name'=>'60岁以上','value'=>0),
		);
		
		$where = array();
		if(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}elseif(session("adminGroup") == self::groupParty){
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
		}
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
	
	function getEducation(){
		$where = array();
		if(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}elseif(session("adminGroup") == self::groupParty){
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
		}
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
		if(session("adminGroup") == self::groupBranch){
			$where[] = "partyid=".session("partyid");
		}elseif(session("adminGroup") == self::groupParty){
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
		}
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
		return M("user")->where($where)->count("userid")->getVal("total");
	}
}

?>