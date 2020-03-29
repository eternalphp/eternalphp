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

namespace App\Admin\Models;
use System\Core\Model;

class handleModel extends BaseModel{
	
    public function __construct() {
        parent::__construct();
    }
	
    //采集微信文章内容
    public function getHtml(){
		if(request("caijiurl") != ''){
			$url = request("caijiurl");
			
			/***
			//获取文章网页源代码
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			**/
			
			$file_contents = https_request($url);

			//$file_contents = file_get_contents($url);
			//获取微信文章内容部分
			$res = preg_match('@<div class="rich_media_content(.*?)</div>@ms',$file_contents,$html_arr);   //0失败 1：成功
			//替换图片属性名称

			if ($res) {
				$file_contents = str_replace('data-src','src',$html_arr[0]);
			}else{  //未获取到微信文章内容，或不是微信文章
				$file_contents = str_replace('data-src','src',$file_contents);
			}

			$file_contents = str_replace('<img', '<img width="325"', $file_contents);

			//抓取所有图片地址
			$img_arr = $this->getimgs($file_contents);

			foreach ($img_arr as $val) {
				$imgName = self::createRandomStr();
				//转移图片到服务器
				$this->downImg($val,$imgName);
				$val = str_replace('"', '', $val);
				$file_contents = str_replace($val,'http://'.$_SERVER['SERVER_NAME'].'/wximg/'.$imgName.'.jpg',$file_contents);
			}
			//return mb_convert_encoding($file_contents, "GBK", "UTF-8");
			return $file_contents;
		}
    }

    //下载微信中的图片到服务器
    private  function downImg($url,$imgname) {
        $ch = curl_init();
        //curl 模拟浏览器访问
        $httpheader = array(
            'Host' => 'mmbiz.qpic.cn',
            'Connection' => 'keep-alive',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,/;q=0.8',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, sdch',
            'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4'
        );
		/***
        $options = array(
            CURLOPT_HTTPHEADER => $httpheader,
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array( $ch , $options );
        $result = curl_exec( $ch );
        curl_close($ch);
		***/
		$result = https_request($url);
		
		if(!file_exists(ROOT.'/wximg/')){
			mkdir(ROOT.'/wximg/',0777,true);
		}
        file_put_contents(ROOT.'/wximg/'.$imgname.'.jpg', $result);
    }

    // 提取字符串中图片url地址
    private function getimgs($str) {
        $reg = '@src="(.*?)"@ms';
        $matches = array();
        preg_match_all($reg, $str, $matches);
        $data = array();
        foreach ($matches[1] as $value) {
            $data[] = $value;
        }
        return $data;
    }

    //生成随机字符串
    private function createRandomStr($length=10){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $strlen = 62;
        while($length > $strlen){
            $str .= $str;
            $strlen += 62;
        }
        $str = str_shuffle($str);
        return substr($str,0,$length);
    }
}
?>
