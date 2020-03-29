<?php

namespace App\Admin\Controllers;

use System\Core\Controller;
use System\Librarys\QRcode;

class qrcodeAction extends  Controller{
 
	function __construct(){
		parent::__construct();
	}
   
	function index(){
		if(request("url") != ''){
			$url = urlencode(request("url"));
			$logo = ROOT . "/public/themes/common/logo.png";
			$data = $this->create($url);
			
			if($data["errcode"] > 0){
				exit($data["errmsg"]);
			}else{
				$content = $data["content"];
			}
			
			$QR = imagecreatefromstring($content); 
			if (file_exists($logo)) {
				$logo = imagecreatefromstring(file_get_contents($logo)); 
				$QR_width = imagesx($QR);//二维码图片宽度 
				$QR_height = imagesy($QR);//二维码图片高度 
				$logo_width = imagesx($logo);//logo图片宽度 
				$logo_height = imagesy($logo);//logo图片高度 
				$logo_qr_width = $QR_width / 5; 
				$scale = $logo_width/$logo_qr_width; 
				$logo_qr_height = $logo_height/$scale; 
				$from_width = ($QR_width - $logo_qr_width) / 2; 
				//重新组合图片并调整大小 
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
				$logo_qr_height, $logo_width, $logo_height); 
			} 
			//输出图片 
			Header("Content-type: image/png");
			ImagePng($QR);
		}
	}
	
	function create($url){
		if($url != ''){
			$value = $url; //二维码内容
			$value = urldecode($value);
			
			$params = parse_url($value);
			$hosts = array();
			$hosts[] = $params["host"];
			if(isset($params["port"]) && $params["port"] != '80') $hosts[] = $params["port"];
			$host = implode(":",$hosts);
			if($host != $_SERVER["HTTP_HOST"]){
				return array(
					'errcode'=>200,
					'errmsg'=>'Illegal request'
				);
			}
			
			$errorCorrectionLevel = 'L';//容错级别 
			$matrixPointSize = 6;//生成图片大小 
			//生成二维码图片
			ob_start();
			QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
			$content = ob_get_contents();
			ob_end_clean();
			return array(
				'errcode'=>0,
				'errmsg'=>'ok',
				'content'=>$content
			);
		}else{
			return array(
				'errcode'=>201,
				'errmsg'=>'URL parameter cannot be empty'
			);
		}
	}
}
?>