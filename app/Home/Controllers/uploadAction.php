<?php

namespace App\Home\Controllers;

use App\Librarys\uploadFile;

class uploadAction extends CommonAction {

	function __construct() {
		parent::__construct();
		$this->upload = $this->librarys("Upload");
	}
	
	function upload(){
		
		$token = request("token");
		if(empty($token) || $token != session("token")){
			exit(fail(array('errmsg'=>'非法请求')));
		}
		
		if(isset($_POST["chunks"])){
			$this->chunkUpload();
			exit;
		}
		
		$this->upload->maxSize = 1024 * 1024 * 10; //最大2M
		$this->upload->allowExts = explode(',', 'ppt,pdf,xls,xlsx,doc,docx,mp4,mp3'); //设置上传文件类型
		$this->upload_path = "/upload/" . date("Ym/d/");
		$this->upload->savePath = public_path($this->upload_path);
		if (!is_dir($this->upload->savePath)) {
			mkdir($this->upload->savePath, 0777, true);
		}
		$res = array();
		if (!$img = $this->upload->uploadOne($_FILES["file"])) {
			$res['errcode'] = 200;
			$res['errmsg'] = $this->upload->getErrorMsg(); //捕获上传异常
			echo fail($res);
		}else {
			$res['title'] = $img[0]["name"];
			$res['filename'] = $this->upload_path . $img[0]["savename"];
			$res['errcode'] = 0;
			$res['errmsg'] = '上传成功';
			echo success($res);
		}
	}
	
	function chunkUpload(){
		$chunks = request("chunks");
		$chunk = request("chunk");
		$md5 = request("md5");
		$id = request("id");
		
		$done = true;
		
		$upload = new uploadFile($_FILES["file"]);
		$upload->allowExts(array('ppt','pdf','xls','xlsx','doc','docx','mp4'));
		if($upload->isValid()){
			$name = sprintf("%s%s_%d.%s",$md5,$id,$chunk,$upload->getClientExtension());
			$tmpfile = public_path("/upload/".date("Ym/d/").$name);
			$result = $upload->putFile($tmpfile);
			if($result){
				for($index = 0;$index < $chunks;$index++){
					$name = sprintf("%s%s_%d.%s",$md5,$id,$index,$upload->getClientExtension());
					$chunk_file = public_path("/upload/".date("Ym/d/").$name);
					if(!file_exists($chunk_file)){
						$done = false;
						break;
					}
				}
			}else{
				echo fail(array('errmsg'=>$upload->getError()));
				exit;
			}
		}else{
			$done = false;
			echo fail(array('errmsg'=>$upload->getError()));
			exit;
		}
		
		$filename = '';
		
		if($done == true){
			$newName = sprintf("%s.%s",uniqid(),$upload->getClientExtension());
			$filename = "/upload/".date("Ym/d/").$newName;
			for($index = 0;$index <= $chunks;$index++){
				$name = sprintf("%s%s_%d.%s",$md5,$id,$index,$upload->getClientExtension());
				$chunk_file = public_path("/upload/".date("Ym/d/").$name);
				file_put_contents(public_path($filename),file_get_contents($chunk_file),FILE_APPEND);
				unlink($chunk_file);
			}
		}
		
		echo success(array('filename'=>$filename,'title'=>$upload->getClientName(),'fileExt'=>$upload->getClientExtension(),'errmsg'=>$upload->getError()));
	}

}

?>