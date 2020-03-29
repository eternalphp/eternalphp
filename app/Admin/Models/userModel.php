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
use App\Librarys\Excel;
use App\Librarys\SLSLog;

class userModel extends Model {
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('用户管理');
		$this->onQuery();
    }
	
    public function index() {
		
        $where = array();
        if (request("keyword") != '') {
            $keyword = request("keyword");
            $where[] = "(t1.name like '%" . $keyword . "%' or t1.mobile like '%" . $keyword . "%')";
        }
        if (request("partyid")>1) {
            $partyid = request("partyid");
			//$partyids = $this->models("party")->getChildId($partyid);

			$userids = $this->table("user_partys")->where("partyid=$partyid")->toList("userid");
			if($userids){
				$where[] = "(t1.partyid=$partyid or t1.userid in (".implode(",",$userids)."))";
			}else{
				$where[] = "t1.partyid=$partyid";
			}
        }
		
        if (request("positionid")>0) {
            $positionid = request("positionid");
            $where[] = "(t1.positionid = $positionid or t1.other_positionid = $positionid)";
        }
		
		if(session("roleid")>2){
            $partyid = (session("masterPartyid"))?session("masterPartyid"):session("partyid");
			$partyids = $this->models("party")->getChildId($partyid);
			$where[] = "t1.partyid in (".implode(",",$partyids).")";
		}
		
		if(request("status")>0){
			$status = request("status");
			$where[] = "t1.status=$status";
		}
		
		$position = $this->table("position")->field()->toList("positionid","name");
		$education = $this->table("educations")->field()->toList("eduid","name");
		$identity = $this->table("user_identity")->toList("identityid","title");
		
		$where[] = "t1.isdelete=0";
		$where[] = "t2.isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table("user as t1")
		->join("party as t2", "t1.partyid=t2.partyid")
		->field("t1.*,t2.name as party_name")
		->where($where)
		->offset(30)
		->order("t1.handleSort desc,t1.sort asc")
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["position"] = array();
				if($val["positionid"] > 0) $list[$k]["position"][] = $position[$val["positionid"]];
				if($val["other_positionid"] > 0) $list[$k]["position"][] = $position[$val["other_positionid"]];
				$list[$k]["position"] = implode("、",$list[$k]["position"]);
				$list[$k]["education"] = isset($education[$val["eduid"]])?$education[$val["eduid"]]:"";
				$list[$k]["identity"] = isset($identity[$val["identityid"]])?$identity[$val["identityid"]]:"";
				
				//多部门
				$partys = $this->table("user_partys as t1")
				->join("party as t2","t1.partyid=t2.partyid")
				->where("t1.userid",$val["userid"])
				->where("t2.isdelete=0")
				->field("t2.name")
				->toList("name");
				if($partys){
					$list[$k]["party_name"] = implode("、",$partys);
				}
				
			}
		}
		return $list;
    }
	
    public function getChildId($parentid) {
        if ($parentid > 0) {
            if (!isset($this->ChildId)) $this->ChildId[] = $parentid;
            $list = $this->model->table('party')->field("partyid")->where("parentid=$parentid")->select();
            if ($list) {
                foreach ($list as $k => $val) {
                    $this->ChildId[] = $val["partyid"];
                    $this->getChildId($val["partyid"]);
                }
            }
            return $this->ChildId;
        }
    }
	
    public function getJson($partyid = 0) {
		if($partyid > 0){
			$ids = $this->getChildId($partyid);
			$list = $this->model->table('user')->field("userid,name,partyid")->where("in",array('partyid'=>$ids))->select();
		}else{
			$list = $this->model->table('user')->field("userid,name,partyid")->select();
		}
        return json_encode($list);
    }
	
    public function save() {
		if(requestInt("userid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
    }
	
	public function create(){
		
		if(request("entrydate") == '') $_POST["entrydate"] = null;
		if(request("joinPartyDate") == '') $_POST["joinPartyDate"] = null;
		
		if($_POST["idcard"] != ''){
			preg_match_all('/[0-9]{6}([0-9]{4})([0-9]{2})([0-9]{2})/',$_POST["idcard"],$matchs);
			if($matchs){
				$_POST["bothday"] = implode("-",array($matchs[1][0],$matchs[2][0],$matchs[3][0]));
			}
		}
		
		$row = $this->table("user")->where("idcard='".$_POST["idcard"]."'")->find();
		if($row){
			if($row["isdelete"] == 0){
				
				$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
				
				echo fail("身份证号码已存在！");
				exit;
			}else{
				$_POST["userid"] = $row["userid"];
				$_POST["isdelete"] = 0;
				$this->modify();
				exit;
			}
		}
		
		$row = $this->table("user")->where("mobile='".$_POST["mobile"]."'")->find();
		if($row){
			if($row["isdelete"] == 0){
				
				$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
				
				echo fail("手机号码已存在！");
				exit;
			}else{
				$_POST["userid"] = $row["userid"];
				$_POST["isdelete"] = 0;
				$this->modify();
				exit;
			}
		}
		
		$_POST['createtime'] = date("Y-m-d H:i:s");
		
		$partyids = explode(",",$_POST["partyid"]);
		if(count($partyids)>1){
			$_POST["partyid"] = $partyids[0];
		}else{
			$partyids = array();
		}
		
	    $userid = $this->model->table("user")->insert($_POST);
		if ($userid > 0) {
			
			if($partyids){
				$data = array();
				foreach($partyids as $partyid){
					$data[] = array(
						'userid'=>$userid,
						'partyid'=>$partyid
					);
				}
				if($data) $this->model->table("user_partys")->insert($data,true);
			}
			
			if($_POST["isPartyMember"] == 1 && isset($_POST["register"])){
				$data = array();
				foreach($_POST["register"]["registerDate"] as $k=>$val){
					if($val != ''){
						$data[] = array(
							'userid'=>$userid,
							'registerDate'=>$val,
							'address'=>$_POST["register"]["address"][$k],
							'createtime'=>date("Y-m-d H:i:s")
						);
					}
				}
				if($data) $this->table("user_register")->insert($data,true);
			}

			$this->models("logs")->addLog(sprintf("添加用户:%s",$_POST["name"]),$_POST);
			
			$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuerys())->where($this->getConditions())->write();
			
			echo success("添加成功");
		} else {
			echo fail("添加失败");
		}
	}
	
	public function modify(){
		if(request("userid")>0){
			$userid = request("userid");
			
			$row = $this->table("user")->where("idcard='".$_POST["idcard"]."'")->where("userid",$userid,'<>')->find();
			if($row){
				if($row["isdelete"] == 0){
					
					$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
					
					echo fail("身份证号码已存在！");
					exit;
				}
			}
			
			$row = $this->table("user")->where("mobile='".$_POST["mobile"]."'")->where("userid",$userid,'<>')->find();
			if($row){
				if($row["isdelete"] == 0){
					
					$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
					
					echo fail("手机号码已存在！");
					exit;
				}
			}
			
			if(request("entrydate") == '') $_POST["entrydate"] = null;
			if(request("joinPartyDate") == '') $_POST["joinPartyDate"] = null;
			if($_POST["idcard"] != ''){
				preg_match_all('/[0-9]{6}([0-9]{4})([0-9]{2})([0-9]{2})/',$_POST["idcard"],$matchs);
				if($matchs){
					$_POST["bothday"] = implode("-",array($matchs[1][0],$matchs[2][0],$matchs[3][0]));
				}
			}
			$_POST['updatetime'] = date("Y-m-d H:i:s");
			
			if($_POST["partyid"] != ''){
				$partyids = explode(",",$_POST["partyid"]);
				if(count($partyids) > 1){
					$_POST["partyid"] = $partyids[0];
				}else{
					$partyids = array();
				}
			}
			
		    $result = $this->model->table("user")->where("userid=$userid")->update($_POST);
			if ($result) {
				
				if($_POST["partyid"] != ''){
					$this->model->table("user_partys")->where("userid=$userid")->delete();
					if($partyids){
						$data = array();
						foreach($partyids as $partyid){
							$data[] = array(
								'userid'=>$userid,
								'partyid'=>$partyid
							);
						}
						if($data) $this->model->table("user_partys")->insert($data,true);
					}
				}
				
				$this->table("user_register")->where("userid=$userid")->delete();
				if($_POST["isPartyMember"] == 1 && isset($_POST["register"])){
					$data = array();
					$register = $_POST["register"];
					foreach($register["registerDate"] as $k=>$val){
						if($val != '' || $register["address"][$k] != ''){
							$data[] = array(
								'userid'=>$userid,
								'registerDate'=>($val == '')?null:$val,
								'address'=>$register["address"][$k],
								'createtime'=>date("Y-m-d H:i:s")
							);
						}
					}
					if($data) $this->table("user_register")->insert($data,true);
				}
				
				$this->models("logs")->addLog(sprintf("编辑用户:%s",$_POST["name"]),$_POST);
				
				$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuerys())->where($this->getConditions())->write();
				
				echo success("更新成功");
			} else {
				echo fail("更新失败");
			}
		}
	}
	
    public function getRow() {
        if (requestInt("userid")>0) {
			$userid = requestInt("userid");
			
			$position = $this->table("position")->toList("positionid","name"); //职位
			$political = $this->table("political")->toList("politicalid","name"); //政治面貌
			$education = $this->table("educations")->toList("eduid","name"); //政治面貌
			
            $row = $this->model->table("user as t1")
			->join("party as t2","t1.partyid=t2.partyid")
			->field("t1.*,t2.name as party_name")
			->where("t1.userid=$userid")
			->find();
			
			if($row){
				$row["register"] = $this->table("user_register")->where("userid=$userid")->select();
				$row["position"] = isset($position[$row["positionid"]])?$position[$row["positionid"]]:"";
				$row["position_other"] = isset($position[$row["other_positionid"]])?$position[$row["other_positionid"]]:"";
				$row["political"] = isset($political[$row["politicalid"]])?$political[$row["politicalid"]]:"";
				$row["education"] = isset($education[$row["eduid"]])?$education[$row["eduid"]]:"";
				
				//多部门
				$partys = $this->table("user_partys as t1")
				->join("party as t2","t1.partyid=t2.partyid")
				->where("t1.userid=$userid")
				->field("t1.partyid,t2.name")
				->select();
				if($partys){
					$partyids = array();
					$party_names = array();
					foreach($partys as $val){
						$party_names[]  = $val["name"];
						$partyids[] = $val["partyid"];
					}
					$row["party_name"] = implode("、",$party_names);
					$row["partyid"] = implode(",",$partyids);
				}else{
					$partys = $this->table("party")->where("partyid",$row["partyid"])->select();
				}
				$row["partys"] = $partys;
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
			return $row;
        }
    }
	
    public function remove() {
        if (requestInt("userid") > 0) {
			$userid = requestInt("userid");
            $result = $this->model->table("user")->where("userid=$userid")->update(array(
				'deletetime' => date("Y-m-d H:i:s"),
				'isdelete'=>1
			));
            if ($result) {
				
				$row = $this->model->table("user")->where("userid=$userid")->find();
				$this->models("logs")->addLog(sprintf("删除用户:%s",$row["name"]));
				
				$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuerys())->where($this->getConditions())->write();
                
                echo success(array(
                    'errmsg' => '数据删除成功'
                ));
            } else {
                echo fail(array(
                    'errmsg' => '数据删除失败'
                ));
            }
        }
    }
	
	//职位
	public function getPosition(){
		return $this->table("position")->select();
	}
	
	//身份
	public function getIdentity(){
		if(session("roleid") > 2){
			return $this->table("user_identity")->where("identityid>2")->select();
		}else{
			return $this->table("user_identity")->select();
		}
	}
	
	//学历
	public function getEducation(){
		return $this->table("educations")->select();
	}
	
	//党费
	public function saveFee(){
		if(request("userids") != ''){
			$userids = request("userids");
			$res = $this->model->table("user")->where("userid in ($userids)")->update(array(
				'year'=>request("year"),
				'month'=>request("month")
			));
			if($res){
				echo success("提交成功");
			}else{
				echo fail("提交成功");
			}
		}
	}
	
	//修改部门
	public function saveModifyParty(){
		if(request("userids") != '' && request("partyid")>0){
			$userids = request("userids");
			
			$row = $this->model->table("user")->where("userid in ($userids)")->find();
			if($row){
				$oldPartyName = $this->table("party")->where("partyid",$row["partyid"])->field('name')->getVal('name');
			}
			
			$res = $this->model->table("user")->where("userid in ($userids)")->update(array(
				'partyid'=>request("partyid"),
			));
			if($res){
				
				$newPartyName = $this->table("party")->where("partyid",request("partyid"))->field('name')->getVal('name');
				
				$this->models("logs")->addLog(sprintf("成员修改部门：%s => %s",$oldPartyName,$newPartyName));
				
				$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuerys())->where($this->getConditions())->write();
				
				echo success("修改成功");
			}else{
				echo fail("修改成功");
			}
		}
	}
	
	public function getSortUser(){
		if(request("userids") != ''){
			$userids = request("userids");
			return $this->model->table("user")->where("userid in ($userids)")->order("handleSort desc")->select();
		}
	}
	
	//修改排序
	public function saveHandleSort(){
		if(request("handleSort")){
			$handleSort = request("handleSort");
			if($handleSort){
				$data = array();
				foreach($handleSort as $userid=>$sort){
					$this->model->table("user")->where("userid=$userid")->update("handleSort=$sort");
				}
			}
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuerys())->where($this->getConditions())->write();
			echo success("操作成功！");
		}
	}
	
	//政治面貌
	public function getPolitical(){
		return $this->table("political")->select();
	}
	
	//导入文件
	public function importData(){
		if(request("filename") != ''){
			$filename = request("filename");
			$filename = public_path($filename);
			$excel = new Excel();
			$line = $excel->read($filename);
			
			$fieldName = array('name','gender','party','mobile','idcard','identity','position','other_position','isPartyMember','education','political','entrydate','joinPartyDate','partyFeeDate','registerDate','address');
			
			$data = array();
			//$data[] = $line[1];
			foreach($line as $k=>$val){
				if($k>0){
					$row = array();
					foreach($fieldName as $kk=>$key){
						if(isset($val[$kk])){
							if(in_array($key,array('workdate','bothday','entrydate','joinPartyDate','partyFeeDate','registerDate'))){
								$date = false;
								if($val[$kk] != '') $date = $excel->getDate($val[$kk]);
								if($date != false){
									$row[$key] = $date;
								}else{
									$row[$key] = $val[$kk];
								}
							}else{
								$row[$key] = $val[$kk];
							}
						}
					}
					if($row) $data[] = $row;
				}
			}
			
			foreach($data as $k=>$vals){
				if($k == 0){
					$data[$k]['sort'] = '排序';
				}else{
					$data[$k]['sort'] = $k;
				}
				
				if($vals["name"] == ''){
					unset($data[$k]);
				}
			}
			
			echo json_encode($data);
			unlink($filename);
		}
	}
	
	public function saveData(){
		
		$Validator = $this->librarys("Validator");
		
		//性别
		$gender = array('男'=>1,'女'=>2);
		
		//职称
		$position = $this->table("position")
		->field("positionid,name")
		->toList("name","positionid");
		
		//用户身份
		$identity = $this->table("user_identity")
		->field("identityid,title")
		->toList("title","identityid");
		
		//政治面貌
		$political = $this->table("political")
		->field("politicalid,name")
		->toList("name","politicalid");
		
		//学历
		$education = $this->table("educations")
		->field("eduid,name")
		->toList("name","eduid");
		
		if(isset($_POST["data"])){
			foreach($_POST["data"] as $k=>$val){
				
				$errList = array();
				
				if($val["name"] == ''){
					$errList[] = "姓名不能为空";
				}
				
				if($val["gender"] != ''){
					if(!isset($gender[$val["gender"]])){
						$errList[] = sprintf("性别格式错误：%s",$val["gender"]);
					}
				}else{
					$errList[] = "性别不能为空";
				}
				
				if($val["idcard"] == ''){
					$errList[] = "身份证号码不能为空";
				}else{
					if(strlen($val["idcard"]) == 18 || strlen($val["idcard"]) == 19){
						$res = $Validator->isIdCard($val["idcard"]);
						if(!$res){
							$errList[] = "身份证号码格式不正确：".$val["idcard"];
						}
					}else{
						$errList[] = "身份证号码格式不正确：".$val["idcard"];
					}
				}
				
				if($val["mobile"] == ''){
					$errList[] = "手机号不能为空";
				}else{
					if(strlen($val["mobile"]) == 11){
						$res = $Validator->isTelNumber($val["mobile"]);
						if(!$res){
							$errList[] = "手机号码格式不正确：".$val["mobile"];
						}else{
							$res = $this->table("user")->where("idcard='".$val["idcard"]."'")->find();
							if($res){
								if($val["mobile"] != $res["mobile"]){
									$res = $this->table("user")->where("mobile='".$val["mobile"]."'")->where("userid",$res["userid"],"<>")->where("isdelete=0")->find();
									if($res){
										$errList[] = sprintf("手机号已存在:%s",$val["mobile"]);
									}
								}
							}
						}
					}else{
						$errList[] = "手机号码格式不正确：".$val["mobile"];
					}
				}
				
				if($val["position"] != ''){
					if(!isset($position[$val["position"]])){
						$errList[] = "未找到职务：".$val["position"];
					}
				}
				
				if($val["other_position"] != ''){
					if(!isset($position[$val["other_position"]])){
						$errList[] = "未找到职务2：".$val["other_position"];
					}
				}
				
				if($val["education"] != ''){
					if(!isset($education[$val["education"]])){
						$errList[] = "未找到学历：".$val["education"];
					}
				}
				
				if($val["party"] != ''){
					$partyid = $this->getPartyId($val["party"]);
					if($partyid == false){
						$errList[] = "未找到部门：".$val["party"];
					}
				}
				
				
				if($val["identity"] != ''){
					if(!isset($identity[$val["identity"]])){
						$errList[] = "未找角色权限：".$val["identity"];
					}
				}
				
				if($val["political"] != ''){
					if(!isset($political[$val["political"]])){
						$errList[] = "政治面貌未找到：".$val["political"];
					}
				}
				
				if($val["isPartyMember"] == '是'){
					$val["isPartyMember"] = 1;
				}else{
					$val["isPartyMember"] = 0;
				}
				
				if($errList){
					$logs[] = sprintf('<font color="red">%s：用户信息导入失败，ERR：%s</font>',$val["name"],implode("、",$errList));
					continue;
				}
				
				if($val["partyFeeDate"] != ''){
					$val["partyFeeDate"] = strtr($val["partyFeeDate"],array('年'=>'-','月'=>''));
				}
				
				if($val["idcard"] != ''){
					preg_match_all('/[0-9]{6}([0-9]{4})([0-9]{2})([0-9]{2})/',$val["idcard"],$matchs);
					if($matchs){
						$val["bothday"] = implode("-",array($matchs[1][0],$matchs[2][0],$matchs[3][0]));
					}
				}
				
				$data = array(
					'name'=>$val["name"],
					'mobile'=>$val["mobile"],
					'gender'=>$gender[$val["gender"]],
					'idcard'=>$val["idcard"],
					'positionid'=>($val["position"] != '')?$position[$val["position"]]:0,
					'other_positionid'=>($val["other_position"] != '')?$position[$val["other_position"]]:0,
					'partyid'=>$partyid,
					'identityid'=>($val["identity"] != '')?$identity[$val["identity"]]:0,
					'isPartyMember'=>$val["isPartyMember"],
					'bothday'=>($val["bothday"] != '')?date("Y-m-d",strtotime($val["bothday"])):null,
					'eduid'=>isset($education[$val["education"]])?$education[$val["education"]]:0,
					'politicalid'=>isset($political[$val["political"]])?$political[$val["political"]]:0,
					'entrydate'=>($val["entrydate"] != '')?$val["entrydate"]:null,
					'joinPartyDate'=>($val["joinPartyDate"] != '')?date("Y-m-d",strtotime($val["joinPartyDate"])):null,
					'registerDate'=>($val["registerDate"] != '')?date("Y-m-d",strtotime($val["registerDate"])):null,
					'year'=>($val["partyFeeDate"] != '')?date("Y",strtotime($val["partyFeeDate"])):0,
					'month'=>($val["partyFeeDate"] != '')?date("n",strtotime($val["partyFeeDate"])):0,
					'address'=>$val["address"],
					'sort'=>$val['sort'],
					'status'=>4
				);
				
				$row = $this->table("user")->where("idcard='".$val["idcard"]."'")->find();
				if(!$row){
					$row = $this->table("user")->where("mobile='".$val["mobile"]."'")->find();
				}
				if($row){
					
					$positions = array();
					if($val["position"] != '') $positions[] = $val["position"];
					if($val["other_position"] != '') $positions[] = $val["other_position"];
					
					unset($data["createtime"],$data["status"]);
					if($row["isdelete"] == 1){
						$data["isdelete"] = 0;
					}
					
					$data['updatetime'] = date("Y-m-d H:i:s");
					$result = $this->model->table("user")->where("userid",$row["userid"])->update($data);
					if($result){
						
						if($val["isPartyMember"] == 1){
							if($val["registerDate"] != null || $val["address"] != ''){
								$res = $this->table("user_register")->where("userid",$row["userid"])->order('id asc')->find();
								if($res){
									$register = array();
									if($val["registerDate"] != '') $register['registerDate'] = $val["registerDate"];
									if($val["address"] != '') $register['address'] = $val["address"];
									$this->table("user_register")->where("id",$res["id"])->update($register);
								}else{
									$register = array();
									$register['userid'] = $row["userid"];
									$register['createtime'] = date("Y-m-d H:i:s");
									if($val["registerDate"] != '') $register['registerDate'] = $val["registerDate"];
									if($val["address"] != '') $register['address'] = $val["address"];
									$this->table("user_register")->insert($register);
								}
							}
						}
	
						$logs[] = sprintf('<font color="green">%s：用户信息更新成功</font>',$val["name"]);
					}else{
						$logs[] = sprintf('<font color="red">%s：用户信息更新失败</font>',$val["name"]);
					}
				}else{
					$data['createtime'] = date("Y-m-d H:i:s");
					$userid = $this->model->table("user")->insert($data);
					if($userid){
						
						if($val["isPartyMember"] == 1){
							if($val["registerDate"] != '' || $val["address"] != ''){
								$register = array();
								$register['userid'] = $userid;
								$register['createtime'] = date("Y-m-d H:i:s");
								if($val["registerDate"] != '') $register['registerDate'] = $val["registerDate"];
								if($val["address"] != '') $register['address'] = $val["address"];
								$this->table("user_register")->insert($register);
							}
						}
						
						$logs[] = sprintf('<font color="green">%s：用户信息导入成功</font>',$val["name"]);
					}else{
						$logs[] = sprintf('<font color="red">%s：用户信息导入失败</font>',$val["name"]);
					}
				}
				
				unset($data,$row);
			}
			
			$this->models("logs")->addLog("导入用户",$_POST["data"]);
			
			echo success(array('logs'=>$logs));
		}
	}
	
	//获取部门id
	/***********
	顶级部门/部门二/子部门一/部门aaa
	
	顶级部门 1
		部门一 2
			子部门一 4
				部门aaa 8
			子部门二 5
		部门二 3
			子部门一 6
				部门aaa 9
			子部门二 7
	*********************************/
	public function getPartyId($partyName){
		if($partyName != ''){
			$list = explode("/",$partyName);
			if(count($list)>1){
				$parentid = 0;
				while($list){
					$name = array_shift($list);
					if($name != ''){
						$row = $this->model->table("party")
						->field("partyid")
						->where("parentid=$parentid")
						->where("name",$name)
						->where("isdelete=0")
						->find();
						if($row){
							if($list){
								$parentid = $row["partyid"];
							}else{
								return $row["partyid"];
							}
						}else{
							return false;
						}
					}
				}
			}else{

				$row = $this->model->table("party")
				->field("partyid")
				->where("name",$partyName)
				->where("isdelete=0")
				->find();
				if($row){
					return $row["partyid"];
				}else{
					return false;
				}
			}
		}
	}

	function exportFile(){
		$where = array();
        if (request("keyword") != '') {
            $keyword = request("keyword");
            $where[] = "(t1.name like '%" . $keyword . "%' or t1.mobile like '%" . $keyword . "%')";
        }
        if (request("partyid")>1) {
            $partyid = request("partyid");
            $where[] = "t1.partyid = $partyid";
        }
		
		if(session("roleid")>2){
            $partyid = session("partyid");
			$partyids = $this->models("party")->getChildId($partyid);
			$where[] = "t1.partyid in (".implode(",",$partyids).")";
		}
		
		if(request("status")>0){
			$status = request("status");
			$where[] = "t1.status=$status";
		}
		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
		$list = $this->table("user as t1")
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as partyName")
		->where($where)
		->select();
		if($list){
			
			$identity = $this->table("user_identity")->toList("identityid","title");
			$position = $this->table("position")->toList("positionid","name");
			$political = $this->table("political")->toList("politicalid","name");
			
			$excel = new Excel();
			$objPHPExcel = $excel->loadExcel();
			//$objPHPExcel->getProperties()->setTitle("成员列表");

			$activeSheet = $objPHPExcel->setActiveSheetIndex(0)
			->setTitle("成员列表")
			->setCellValue('A1', '序号')
			->setCellValue('B1', '姓名')
			->setCellValue('C1', '性别')
			->setCellValue('D1', '部门')
			->setCellValue('E1', '手机号')
			->setCellValue('F1', '身份证号码')
			->setCellValue('G1', '角色权限')
			->setCellValue('H1', '党内职位1')
			->setCellValue('I1', '党内职位2')
			->setCellValue('J1', '是否党员')
			->setCellValue('K1', '出生日期')
			->setCellValue('L1', '文化程度')
			->setCellValue('M1', '政治面貌')
			->setCellValue('N1', '参加工作时间')
			->setCellValue('O1', '来本单位时间')
			->setCellValue('P1', '入党时间')
			->setCellValue('Q1', '党费交纳至')
			->setCellValue('R1', '双报到时间')
			->setCellValue('S1', '所在街镇社区');
			
			$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:S1')->applyFromArray(array(
				'font' => array (
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
				)
			));
			
			$education = $this->table("educations")->toList("eduid","name");
	
			foreach($list as $k=>$val){
				$activeSheet->setCellValue("A".($k+2),$k+1);
				$activeSheet->setCellValue("B".($k+2),$val["name"]);
				$activeSheet->setCellValue("C".($k+2),($val["gender"] == 1)?"男":"女");
				$activeSheet->setCellValue("D".($k+2),$val["partyName"]);
				$activeSheet->setCellValue("E".($k+2),$val["mobile"]);
				if(session("roleid")>2){
					if($val["idcard"] != ''){
						$val["idcard"] = substr_replace($val["idcard"],'**********',4,10);
					}
				}else{
					$val["idcard"] .= ' ';
				}
				$activeSheet->setCellValue("F".($k+2),$val["idcard"]);
				$activeSheet->setCellValue("G".($k+2),isset($identity[$val["identityid"]])?$identity[$val["identityid"]]:"/");
				$activeSheet->setCellValue("H".($k+2),isset($position[$val["positionid"]])?$position[$val["positionid"]]:"/");
				$activeSheet->setCellValue("I".($k+2),isset($position[$val["other_positionid"]])?$position[$val["other_positionid"]]:"/");
				$activeSheet->setCellValue("J".($k+2),($val["isPartyMember"] == 1)?"是":"否");
				$activeSheet->setCellValue("K".($k+2),$val["bothday"]);
				$activeSheet->setCellValue("L".($k+2),isset($education[$val['eduid']])?$education[$val['eduid']]:'/');
				$activeSheet->setCellValue("M".($k+2),isset($political[$val["politicalid"]])?$political[$val["politicalid"]]:"/");
				$activeSheet->setCellValue("N".($k+2),$val["workdate"]);
				$activeSheet->setCellValue("O".($k+2),$val["entrydate"]);
				$activeSheet->setCellValue("P".($k+2),($val["isPartyMember"] == 1)?$val["joinPartyDate"]:"");
				$activeSheet->setCellValue("Q".($k+2),($val["isPartyMember"] == 1)?sprintf("%d年%d月",$val["year"],$val["month"]):"");
				$activeSheet->setCellValue("R".($k+2),($val["isPartyMember"] == 1)?$val["registerDate"]:"");
				$activeSheet->setCellValue("S".($k+2),($val["isPartyMember"] == 1)?$val["address"]:"");
			}
			
			for($column = 1;$column<=18;$column++){
				$char = \PHPExcel_Cell::stringFromColumnIndex($column);
				$objPHPExcel->getActiveSheet()->getColumnDimension($char)->setWidth(30);
			}
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.date("Ymd").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		}
	}
}
?>
