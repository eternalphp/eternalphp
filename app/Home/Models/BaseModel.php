<?php

namespace App\Home\Models;
use System\Core\Model;


class BaseModel extends Model {
	
	const groupLeader = 1; //平台管理员、党委领导 1,4,7
	const groupParty = 2;  //总支成员 2,5,8
	const groupBranch = 3; //支部成员 3,6,9
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getUserName($userid){
		if($userid>0){
			return $this->model->table("user as t1")->join("party as t2","t1.partyid=t2.partyid")
			->field("t1.name,t2.name as partyName")
			->where("t1.userid=$userid")
			->where("t2.isdelete=0")
			->find();
		}
	}
	
	public function getChildId($parentid){
		if($parentid>0){
			if(!isset($this->ChildId)) $this->ChildId[] = $parentid;
			$list = $this->model->table('party')->field("partyid")->where("parentid=$parentid and isdelete=0")->select();
			if($list){
				foreach($list as $k=>$val){
					$this->ChildId[] = $val["partyid"];
					$this->getChildId($val["partyid"]);
				}
			}
			return $this->ChildId;
		}
	}
	
	//登录用户当前部门下的成员
	public function getPartyUser(){
		if(session("partyid")>0){
			$userid = session("userid");
			$where = array();
			$partyids = $this->getChildId(session("partyid"));
			if($partyids){
				$where[] = "partyid in (".implode(",",$partyids).")";
			}
			$where[] = "userid<>$userid";
			$where[] = "isdelete=0";
			$where = implode(" and ",$where);
			
			return M("user")->field()->where($where)->select();
		}
	}

	
	protected function getSendUsers(){
		$sendUser = array();
		if(request("userids")!=''){
			$userids = explode("|",request("userids"));
			foreach($userids as $k=>$userid){
				if($userid!=''){
					if(!in_array($userid,$sendUser)){
						$sendUser[] = $userid;
					}
				}
			}
		}
		return $sendUser;
	}
	
	public function uploadPath(){
		$savePath = "/upload/" . date("Ym/d/");
		$path = public_path($savePath);
		if(!file_exists($path)){
			mkdir($path,0777,true);
		}
		return $savePath;
	}
	
	//上传base64图片文件
	public function uploadBase64File($base64_image_content){
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
			$type = $result[2];
			if(in_array(strtolower($type),['jpg','jpeg','gif','png'])){
				$filename = sprintf("%s%s.%s",$this->uploadPath(),uniqid(),$type);
				file_put_contents(public_path($filename),base64_decode(str_replace($result[1], '', $base64_image_content)));
				return $filename;
			}
		}
		return false;
	}
	
	//增加积分记录
	public function scoreAdd($itemid,$newsid = 0){
		if($itemid>0 && session("userid")>0){
			$row = M("score_rules")->field("score")->where("itemid=$itemid")->find();
			if($row){
				$this->model->table("score")->insert(array(
					'userid'=>session("userid"),
					'partyid'=>session("partyid"),
					'itemid'=>$itemid,
					'newsid'=>$newsid,
					'score'=>$row["score"]
				));
				$score = $row["score"];
				$this->model->table("user")->where("userid",session("userid"))->update("score=score+$score");
			}
		}
	}
	

}

?>