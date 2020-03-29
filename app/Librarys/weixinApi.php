<?php 

namespace App\Librarys;
use System\Core\Debug;
use SoapClient;

class weixinApi {
	
	private $appid;
	private $secrect;
	private $redirect_uri;
	private $scope; //snsapi_base snsapi_userinfo snsapi_privateinfo
	private $agentid;
	
	public function __construct(){
		$this->appid = C("appid");
		$this->secrect = C("secrect");
		$this->redirect_uri = C("redirect_uri");
		$this->scope = 'snsapi_userinfo';
		$this->agentid = C("agentid");
	}
	
	public function login(){
		header("location:".$this->getAuthorizeUrl());
	}
	
	public function getAccessToken(){
		$data = $this->getJson('access_token.json');
		if($data && (time() - $data["time"] < $data["expires_in"])){
			return $data['access_token'];
		}else{
			$url = sprintf("https://jwwx.gaj.sh.gov.cn/cgi-bin/gettoken?corpid=%s&corpsecret=%s",$this->appid,$this->secrect);
			$json = https_request($url);
			$data = json_decode($json,true);
			$this->putJson('access_token.json',$data);
			return $data['access_token'];
		}
	}
	
	public function scope($scope){
		$this->scope = $scope;
		return $this;
	}
	
	public function agentid($agentid){
		$this->agentid = $agentid;
		return $this;
	}
	
	public function putJson($filename,$data){
		$data["time"] = time();
		$filename = '/access_token/'.$filename;
		file_put_contents(storage_path($filename),json_encode($data));
	}
	
	public function getJson($filename){
		$filename = '/access_token/'.$filename;
		if(file_exists(storage_path($filename))){
			$json = file_get_contents(storage_path($filename));
			return json_decode($json,true);
		}else{
			return false;
		}
	}
	
	public function getUser(){
		$access_token = $this->getAccessToken();
		$code = request("code");
		$url = sprintf("https://jwwx.gaj.sh.gov.cn/cgi-bin/user/getuserinfo?access_token=%s&code=%s",$access_token,$code);
		$json = https_request($url);
		return json_decode($json,true);
	}
	
	public function getUserInfo($user_ticket){
		$access_token = $this->getAccessToken();
		$url = sprintf("https://jwwx.gaj.sh.gov.cn/cgi-bin/user/getuserdetail?access_token=%s",$access_token);
		$json = https_request($url,array('user_ticket'=>$user_ticket));
		return json_decode($json,true);
	}
	
	//获取4a中用户数据
	public function getUserByCode($code){
        $client = new SoapClient("http://15.75.0.64:9000/ws/face?wsdl");
        $method = "request";
        $params = array('arg0'=>'1','arg1'=>'<req><app><PASSWD>admin</PASSWD><APP_CODE>310097000001</APP_CODE><USERNAME>admin</USERNAME></app></req>','arg2'=>'GetUserAndExtUserByCode', 'arg3'=>'<req><user><USER_CODE>'.$code.'</USER_CODE></user></req>');
        try{
            $result = $client->$method($params);
            $xml = simplexml_load_string($result->return);
            $xmljson = json_encode($xml);
            $data = json_decode($xmljson,true);
			return $data["data"]["users"]["user"];
        }catch(Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
	}
	
	public function getAuthorizeUrl(){
		return sprintf("https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&agentid=%s&state=STATE#wechat_redirect",$this->appid,urlencode($this->redirect_uri),$this->scope,$this->agentid);
	}
	
	public function getJsApiTicket() {
		$data = $this->getJson('ticket.json');
		if ($data) {
			return $data['ticket'];
		} else {
			$accessToken = $this->getAccessToken();
			$URL = sprintf("https://jwwx.gaj.sh.gov.cn/cgi-bin/get_jsapi_ticket?access_token=%s",$accessToken);
			$json = https_request($URL);
			$data = json_decode($json,true);
			$this->putJson("ticket.json",$data);
			return $data["ticket"];
		}
	}
	
	public function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();
		if(isset($_SERVER["HTTPS"])){
			$url = getHost().$_SERVER["REQUEST_URI"];
		}else{
			$url = getHost('off').$_SERVER["REQUEST_URI"];
		}
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"beta" => true,
			"debug" => false,
			"appId" => $this->appid,
			"nonceStr" => $nonceStr,
			"timestamp" => $timestamp,
			"signature" => $signature,
			"jsApiList" => array(
				'checkJsApi',
				'onMenuShareAppMessage',
				'onMenuShareWechat',
				'onMenuShareTimeline',
				'shareAppMessage',
				'shareWechatMessage',
				'startRecord',
				'stopRecord',
				'onVoiceRecordEnd',
				'playVoice',
				'pauseVoice',
				'stopVoice',        
				'uploadVoice',
				'downloadVoice',
				'chooseImage',
				'previewImage',
				'uploadImage',
				'downloadImage',
				'getNetworkType',
				'openLocation',
				'getLocation',
				'hideOptionMenu',
				'showOptionMenu',
				'hideMenuItems',
				'showMenuItems',
				'hideAllNonBaseMenuItem',
				'showAllNonBaseMenuItem',
				'closeWindow',
				'scanQRCode',
				'previewFile',
				'openEnterpriseChat',
				'selectEnterpriseContact',
				'onHistoryBack',
				'openDefaultBrowser'
			)
		);
		
		return $signPackage;
	}
	
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str.= substr($chars, mt_rand(0, strlen($chars) - 1) , 1);
		}
		return $str;
	}
}
?>