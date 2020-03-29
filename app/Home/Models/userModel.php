<?php

namespace App\Home\Models;

class userModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	
	function getUser($userid){
		$row = $this->model->table("user as t1")
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as partyName")
		->where("t1.userid",$userid)
		->find();
		return $row;
	}
	
	function getUserByIDcard($idcard){
		$row = $this->model->table("user as t1")
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as partyName")
		->where("t1.idcard",$idcard)
		->find();
		return $row;
	}
	
	function saveUser($userid,$orgCode,$orgFullName){
		$this->model->table("user")->where("userid=$userid")->update(array(
			'orgCode'=>$orgCode,
			'orgFullName'=>$orgFullName
		));
	}

	
	//关注
	public function subscribe($userid){
		if($userid>0){
			$data = array();
			$res = $this->librarys("weixinAPI")->getUser($userid);
			if(isset($res["avatar"])){
				$data["avatar"] = $res["avatar"];
			}
			$data["status"] = 1;
			$row = $this->model->table("user")->field()->where("userid=$userid")->find();
			if($row){
				$this->model->table("user")->where("userid=$userid")->update($data);
			}
		}
	}
	
	//取消关注
	public function unsubscribe($userid){
		if($userid>0){
			$this->model->table("user")->where("userid=$userid")->update(array(
				'avatar'=>'',
				'status'=>4
			));
		}
	}
}

?>