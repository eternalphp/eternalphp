<?php

namespace App\Home\Controllers;
use App\Librarys\weixinApi;

class weixinAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->weixinApi = new weixinApi();
		$this->model = $this->models("user");
	}
	
	function login(){
		$this->weixinApi->login();
	}
	
	function getUser(){
		$res = $this->weixinApi->getUser();
		if(isset($res["UserId"])){
			$row = $this->model->getUserByIDcard($res["UserId"]);
			if($row){
				session("userid",$row["userid"]);
				session("idcard",$row["idcard"]);
				session("name",$row["name"]);
				session("partyid",$row["partyid"]);
				session("party",$row["partyName"]);
				
                if(row["orgCode"] != ''){
				    session("orgCode",$row["orgCode"]);
				    session("orgFullName",$row["orgFullName"]);
                }else{
    				$res = $this->weixinApi->getUserByCode($row["idcard"]);
    				if($res){
    				    if(isset($res["ORG_CODE"])){
        				    $this->model->saveUser($row["userid"],$res["ORG_CODE"],$res["FULL_NAME"]);
        				    session("orgCode",$res["ORG_CODE"]);
        				    session("orgFullName",$res["FULL_NAME"]);
    				    }
    				}
                }
				
				header("location:".session("backurl"));
			}else{
				$this->noPowerPage();
			}
		}else{
			$this->noPowerPage();
		}
	}

}

?>