<?php

namespace App\Home\Models;

class workModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index(){
		return $this->model->table("work")->where("isdelete=0")->select();
	}
	
	public function getRow(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			return $this->model->table("work")->where("newsid=$newsid")->find();
		}else{
			return $this->model->table("work")->find();
		}
	}
}

?>