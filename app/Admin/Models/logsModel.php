<?php

namespace App\Admin\Models;
use System\Core\Model;

class logsModel extends Model {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$where = array();
		if(request("starttime")!='' && request("endtime")!=''){
			$starttime = request("starttime");
			$endtime = request("endtime");
			$endtime = date("Y-m-d H:i:s",strtotime("+1 day",strtotime($endtime)));
			$where[] = "t1.createtime between '$starttime' and '$endtime'";
		}
		if(request('keyword')!=''){
			$keyword = request('keyword');
			$where[] = "(t1.title like '%".$keyword."%' or t2.username like '%".$keyword."%')";
		}
		$where = implode(' and ',$where);
		$list = $this->model->table('logs as t1')
		->join("admin as t2","t1.userid=t2.userid")
		->field("t1.id,t1.userid,t1.title,t1.ip,t1.createtime,t2.username")
		->where($where)
		->offset(C("offset"))
		->order("t1.id desc")
		->select();
		return $list;
	}

	public function addLog($title,$data = array()) {
		$this->model->table("logs")->insert(array(
			"title" => $title,
			"data" => ($data)?json_encode($data):json_encode($_REQUEST),
			"url" => $_SERVER["REQUEST_URI"],
			"ip" => get_ip(),
			"userid" => $this->session("userid"),
			"createtime" => date("Y-m-d H:i:s")
		));
	}
	
	public function remove(){
		if(request("id")>0){
			$id = request("id");
			$result = $this->model->table("logs")->where("id",$id)->delete();
			if($result){
				echo success(array('errmsg'=>'ok'));
			}else{
				echo fail(array('errmsg'=>'fail'));
			}
		}
	}
}

?>