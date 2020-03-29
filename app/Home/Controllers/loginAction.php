<?php

namespace App\Home\Controllers;
use System\Core\Controller;
use App\Librarys\SOSLogin;

class loginAction extends Controller {

	function __construct() {
		parent::__construct();
		$this->model = $this->models("user");
		$this->SOSLogin = new SOSLogin();
	}
	
	function index(){
		$this->view();
	}
	
	//单点登录
	function sosLogin(){
		if(request("ticket") != ''){
			$ticket = request("ticket");
			$user = $this->SOSLogin->getUser($ticket);
			if(isset($user["serviceResponse"]["authenticationSuccess"]["user"])){
				$userids = $user["serviceResponse"]["authenticationSuccess"]["user"];
				$userids = explode(" ",$userids);
				
				$row = $this->model->getUserByIDcard($userids[1]);
				if($row){
					session("idcard",$userids[1]);
					session("name",$userids[0]);
					session("partyid",$row["partyid"]);
					session("userid",$row["userid"]);
					session("party",$row["partyName"]);
					header("location:".session("backurl"));
				}else{
					$this->noPowerPage();
				}
				

			}else{
				$this->noPowerPage();
			}
		}
	}
	
	function verifyLogin(){
		if(request("username") != '' && request("password") != ''){
			$username = request("username");
			$password = request("password");
			
			$res = $this->SOSLogin->verify($username,$password);
			if($res["isSuccess"]){
				$row = $this->model->getUserByIDcard($username);
				if($row){
					session("idcard",$username);
					session("name",$row["name"]);
					session("partyid",$row["partyid"]);
					session("userid",$row["userid"]);
					session("party",$row["partyName"]);
					header("location:".session("backurl"));
				}else{
					$this->noPowerPage();
				}
			}else{
				$this->noPowerPage($res["errMsg"]);
			}
		}
	}
	
	function noPowerPage($message = '您没有权限查看！'){
		$data["message"] = $message;
		$this->load->view("Error/message",$data);
		exit;
	}

}

?>