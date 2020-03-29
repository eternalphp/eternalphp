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

class BaseModel extends Model {
	
	const AGENTID = '1000002'; //应用ID
	
    public function __construct() {
        parent::__construct();
    }
	
	protected function getSendUsers(){
		$sendUser = array();
		if(request("userids") != ''){
			$userids = request("userids");
			if(strstr($userids,"|")){
				$userids = explode("|",request("userids"));
			}else{
				$userids = explode(",",request("userids"));
			}
			foreach($userids as $k=>$userid){
				if($userid!=''){
					if(!in_array($userid,$sendUser)){
						$sendUser[] = $userid;
					}
				}
			}
		}
		return $sendUser;
	}
	

	//阅读人员
	protected function getReadUsers($table,$keyField,$id){
		if($id>0){
			$data = array();
			$data["read"] = array();
			$data["unread"] = array();
			
			$list = $this->model->table("$table as t1")
			->join("user as t2","t1.userid=t2.userid")
			->field("t1.read,t2.userid,t2.name")
			->where("t1.$keyField=$id")
			->where("t2.isdelete=0")->select();
			
			if($list){
				foreach($list as $k=>$val){
					if($val["read"]==1){
						$data["read"][] = $val["name"];
					}else{
						$data["unread"][] = $val["name"];
					}
				}
			}
			return $data;
		}
	}
	
	protected function getUserName($userid){
		if($userid>0){
			$row = $this->model->table("user")->field("name")->where("userid=$userid")->where("isdelete=0")->find();
			if($row){
				return $row["name"];
			}else{
				return false;
			}
		}
	}
	
	//增加积分记录
	public function scoreAdd($userid,$itemid,$newsid = 0){
		if($itemid>0 && $userid>0){
			$row = M("score_rules")->field("score")->where("itemid=$itemid")->find();
			if($row){
				$user = M("user")->where("userid=$userid")->find();
				if($user){
					$this->model->table("score")->insert(array(
						'userid'=>$userid,
						'partyid'=>$user["partyid"],
						'itemid'=>$itemid,
						'newsid'=>$newsid,
						'score'=>$row["score"]
					));
					$score = $row["score"];
					$this->model->table("user")->where("userid=$userid")->update("score=score+$score");
				}
			}
		}
	}
	
	public function scoreMinus($userid,$itemid,$newsid = 0){
		if($itemid>0 && $userid>0 && $newsid>0){
			$row = $this->model->table("score")->where("userid=$userid")->where("itemid=$itemid")->where("newsid=$newsid")->find();
			if($row){
				$score = $row["score"];
				$this->model->table("user")->where("userid=$userid")->update("score=score-$score");
				$this->model->table("score")->where("id",$row["id"])->delete();
			}
		}
	}
	
	//自动抓取秀米远程图片
	public function getHtmlContent($content){
		
		$content = str_replace('&quot;','',$content);
		$content = stripslashes($content);
		
		return $content;
		
		//处理 秀米、公众号中的图片
		$imgsList = $this->getimgs($content);
		if($imgsList){
			
			$filePath = "/upload/catchimage/".date("Ymd/");
			if(!file_exists(ROOT . $filePath)){
				mkdir(ROOT . $filePath,0777,true);
			}
			
			$imgScore = array();
			foreach($imgsList as $k=>$imgUrl){
				if(strstr($imgUrl,'https://') || strstr($imgUrl,'http://')){
					
					//处理图片webp格式
					if(strstr($imgUrl,"xmwebp")){
						$imgUrls = explode("?",$imgUrl);
						$newImgUrl = $imgUrls[0];
					}elseif(strstr($imgUrl,"tp=webp")){
						$newImgUrl = str_replace("&tp=webp","",$imgUrl); 
					}elseif(strstr($imgUrl,"format,webp")){
						$newImgUrl = str_replace("format,webp","format,jpg",$imgUrl);
					}else{
						$newImgUrl = $imgUrl;
					}
					
					$imgContent = https_request($newImgUrl);
					$name = basename($newImgUrl);
					
					if(strstr($name,'wx_fmt')){
						$names = explode("?",$name);
						$params = explode("&",$names[1]);
						if($params){
							foreach($params as $vals){
								list($key,$fileExt) = explode("=",$vals);
								if($key == 'wx_fmt'){
									$filename = sprintf("%s%s.%s",$filePath,uniqid(),$fileExt);
									break;
								}
							}
						}
					}else{
						$names = explode("?",$name);
						$params = explode(".",$names[0]);
						if(isset($params[1]) && in_array(strtolower($params[1]),array('png','jpg','jpeg','gif','bmp'))){
							$filename = $filePath . $names[0];
						}else{
							if(strstr($newImgUrl,"format,jpg")){
								$filename = sprintf("%s%s.%s",$filePath,uniqid(),'jpg');
							}else{
								$filename = sprintf("%s%s.%s",$filePath,uniqid(),'png');
							}
						}
					}
					
					file_put_contents(ROOT . $filename,$imgContent);
					$imgScore[$imgUrl] = $filename;
					
					//超过200k非gif文件压缩 
					$filesize = filesize(ROOT . $filename)/1024;
					if($filesize > 200){
						$fileExts = explode(".",basename($filename));
						if(strtolower($fileExts[1]) != 'gif'){
							$this->compressImage($filename);
						}
					}
				}
			}
			
			if($imgScore){
				$content = strtr($content,$imgScore);
			}
		}
		
		return $content;
	}
	
	//压缩图片
	private function compressImage($filename){
		$image = $this->librarys("Image");
		$image->thumb(ROOT . $filename, ROOT . $filename, $type = '', 800, 800);
	}
	
    // 提取字符串中图片url地址
    private function getimgs($str) {
		
		$data = array();
		
        $reg = '@src="(.*?)"@ms';
        $matches = array();
        preg_match_all($reg, $str, $matches);
        foreach ($matches[1] as $value) {
			if(strstr($value,"player.html") == false){
				$data[] = $value;
			}
        }
		
        $reg = '@background-image:\s?url\((.*?)\)@ms';
        $matches = array();
        preg_match_all($reg, $str, $matches);
        foreach ($matches[1] as $value) {
            $data[] = $value;
        }
		
        $reg = '@background:\s?url\((.*?)\)@ms';
        $matches = array();
        preg_match_all($reg, $str, $matches);
        foreach ($matches[1] as $value) {
			$data[] = $value;
        }
		
        $reg = '@-webkit-border-image:\s?url\((.*?)\)@ms';
        $matches = array();
        preg_match_all($reg, $str, $matches);
        foreach ($matches[1] as $value) {
			$data[] = $value;
        }
		
        return $data;
    }
}
?>
