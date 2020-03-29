<?php

namespace App\Home\Models;
use System\Core\Model;


class loginModel extends Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	function login(){
		if(request("mobile") != ''){
			$mobile = request("mobile");
			$res = $this->model->table("user")->field()->where("mobile='$mobile'")->where("isdelete=0")->find();
			if($res){
				$this->session("userid",$res["userid"]);
				$this->models("user")->login($res["userid"]);
				$this->session("isLogin",false);
				echo success('登录成功');
			}else{
				echo fail('登录失败');
			}
		}
	}
	
}

?>