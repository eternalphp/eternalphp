<?php 

namespace App\Librarys;

class SOSLogin {
	
	private $redirectUrl = 'https://ghdj.gaj.sh.gov.cn/login/sos';
	//private $sosServer = 'http://47.100.173.115:9090';
	private $sosServer = 'http://15.75.0.65:8080';
	
	
	
	public function __construct(){

	}
	
	//登录获取ticket
	public function login(){
		$url = sprintf("%s/cas/login?service=%s",$this->sosServer,urlencode($this->redirectUrl));
		header("location:$url");
	}

	
	//获取用户信息 记录session
	public function getUser($ticket){
		$url = sprintf("%s/cas/p3/serviceValidate?service=%s&ticket=%s&format=json",$this->sosServer,urlencode($this->redirectUrl),$ticket);
		$json = https_request($url);
		return json_decode($json,true);
	}
	
	
	//退出登录
	public function logout(){
		$url = sprintf("%s/cas/logout",$this->sosServer);
		header("location:$url");
	}
	
	//3.4.1	密码校验
	public function verify($username,$password){
		$url = sprintf("%s/cas/pw/verify",$this->sosServer);
		
		$headers = array(
			"Content-type: application/json;charset='utf-8'",
			"Accept: application/json",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
		);
		$data = array('username'=>$username,'encryptPassword'=>sha1($password));
		$json = https_request($url,json_encode($data),array('header'=>$headers));
		return json_decode($json,true);
	}
}
?>