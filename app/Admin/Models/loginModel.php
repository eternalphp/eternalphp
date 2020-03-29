<?php

namespace App\Admin\Models;
use System\Core\Model;
use App\Librarys\SLSLog;
use App\Librarys\weixinApi;

class loginModel extends Model {
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('系统登录');
		$this->onQuery();
		$this->SLSLog->handle(SLSLog::OPT_LOGIN);
    }
	
    public function checklogin() {
		
        $username = chksql(trim($_POST["username"]));
        $password = chksql(trim($_POST["password"]));
        if ($username == "") {
			$this->SLSLog->error(SLSLog::ERR_USER_INPUT)->write();
            exit(fail(array('msg' => L("username_empty_msg"))));
        }
        if ($password == "") {
			$this->SLSLog->error(SLSLog::ERR_USER_INPUT)->write();
            exit(fail(array('msg' => L("password_empty_msg"))));
        }
		
        $row = $this->model->table("admin as t1")
		->join("user as t2", "t1.userid=t2.userid")
		->field("t1.*,t2.userid,t2.name,t2.idcard,t2.partyid,t2.positionid,t2.other_positionid")
		->where("t1.username='$username'")
		->order("t1.isdelete asc")
		->find();
        if (!$row) {
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_ACCOUNT)->write();
            exit(fail(array('msg' => L("username_failed_msg"))));
        }
		
        if ($row["password"] == md5($password)) {
			
			if($row["isdelete"] == 1){
				$this->SLSLog->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_AUTH)->write();
				exit(fail(array('errmsg' => '帐号已删除！')));
			}
			
			if($row["status"] == 2){
				$this->SLSLog->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_FREEZE)->write();
				exit(fail(array('errmsg' => '帐号禁用！')));
			}
			
			if($row["userid"] == 0){
				$this->SLSLog->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_AUTH)->write();
				exit(fail(array('errmsg' => '用户已删除！')));
			}
			
            $this->session("userid", $row["userid"]);
            $this->session("username", $row["username"]);
            $this->session("partyid", $row["partyid"]);
			$this->session("masterPartyid",false); //是否总支partyid
            $this->session("roleid", $row["roleid"]);
            
			if($row["orgCode"] != ''){
				$this->session("orgCode",$row["orgCode"]);
				$this->session("orgFullName",$row["orgFullName"]);
			}else{
				$weixinApi = new weixinApi();
				$res = $weixinApi->getUserByCode($row["idcard"]);
				if($res){
				    if(isset($res["ORG_CODE"])){
    					$this->model->table("user")->where("userid",$row["userid"])->update(array(
    						'orgCode'=>$res["ORG_CODE"],
    						'orgFullName'=>$res["FULL_NAME"]
    					));
    					$this->session("orgCode",$res["ORG_CODE"]);
    					$this->session("orgFullName",$res["FULL_NAME"]);
				    }
				}
			}
            
			$partys = $this->table("user_partys as t1")
			->join("party as t2","t1.partyid=t2.partyid")
			->where("t1.userid",$row["userid"])
			->where("t2.isdelete=0")
			->field("t2.*")
			->select();
			if($partys){
				foreach($partys as $val){
					if($val["ismaster"] == 1){
						$this->session("masterPartyid",$val["partyid"]);
					}else{
						$this->session("partyid",$val["partyid"]);
					}
				}
			}
			
			$positionids = array();
			$positionids[] = $row["positionid"];
			if($row["other_positionid"]>0) $positionids[] = $row["other_positionid"];
			$this->session("positionid",$positionids);
			 
            $this->session("status", $row["status"]);
			if($row["roleid"] == 1){
				$this->session("isAdmin", true);
			}else{
				$this->session("isAdmin", false);
			}
			
			if(in_array($row["roleid"],array(1,2,5))){
				$this->session("partyAdmin", true);
			}else{
				$this->session("partyAdmin", false);
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
            exit(success(array('errmsg' => '登录成功！')));
        } else {
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_ACCOUNT)->write();
            exit(fail(array('errmsg' => '密码错误！')));
        }
    }
	
    public function loginOut() {
        $this->session();
		$this->SLSLog->handle(SLSLog::OPT_LOGOUT)->write();
        if ($this->session("username") == "") {
            echo success(array('errmsg' => '退出成功'));
        } else {
            echo fail(array('errmsg' => '退出失败'));
        }
    }
}
?>