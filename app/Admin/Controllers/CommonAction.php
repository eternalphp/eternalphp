<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
//全局控制器类

namespace App\Admin\Controllers;
use System\Core\Controller;
use System\Core\Debug;

class CommonAction extends Controller {
	
    function __construct() {
        parent::__construct();
		
        defined("ACTION") || define("ACTION", $this->postAction());
		
		new Debug($this->session("last_time"));
		
		//验证登录过期
		if($this->session("last_time") > 0 && (time() - $this->session("last_time")) > C("sess_expiration")){
			new Debug("logout:" . $this->session("last_time"));
			$this->session();
		}else{
			$this->session("last_time",time());
		}
		
        if ($this->session("username") == "") {
			echo "<script>top.location.href='".autolink(array("login"))."';</script>";
			exit;
        }
		
		//搜索的时候，自动将page设为1
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$_GET["page"] = 1;
		}
    }
	
	function postAction() {
		$action = get('class');
		if (get("method") == "add") {
			return U(implode('/',array($action,'save')));
		}elseif (get("method") == "edit") {
			return U(implode('/',array($action,'save')),get());
		}else{
			return $_SERVER["REQUEST_URI"];
		}
	}
	
	function search($keys = array()){
		$params = array(get('class'), get('method'), 'page' => '{page}');
		if($keys){
			foreach($keys as $key){
				if(request($key)){
					$params[$key] = request($key);
				}
			}
		}
		return autolink($params);
	}
	
	function noPowerPage($message = '您没有操作权限！'){
		$data["message"] = $message;
		$this->load->view("Error/message",$data);
		exit;
	}
}
?>