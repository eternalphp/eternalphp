<?php

namespace App\Admin\Controllers;

class uploadAction extends CommonAction {
	function __construct() {
		parent::__construct();
		$this->upload = $this->librarys("Upload");
	}
	
	// 图片上传
	function upload() {
		$this->upload->maxSize = 1024 * 1024 * 5; //最大2M
		$this->upload->allowExts = explode(',', 'jpg,gif,png,bmp'); //设置上传文件类型
		$this->upload_path = "/upload/" . date("Ym/d/");
		$this->upload->savePath = public_path($this->upload_path);
		if (!is_dir($this->upload->savePath)) {
			mkdir($this->upload->savePath, 0777, true);
		}
		$res = array();
		if (!$img = $this->upload->uploadOne($_FILES["Filedata"])) {
			$res['errcode'] = 200;
			$res['errmsg'] = $this->upload->getErrorMsg(); //捕获上传异常
		}else {
			$res['title'] = $img[0]["name"];
			$res['filename'] = $this->upload_path . $img[0]["savename"];
			$res['errcode'] = 0;
			$res['errmsg'] = '上传成功';
		}
		exit(json_encode($res));
	} 
	
	// 附件上传
	function upfile() {

		$this->upload->maxSize = 1024 * 1024 * 5; //最大2M
		$this->upload->allowExts = explode(',', 'zip,rar,pdf,xls,xlsx,docx'); //设置上传文件类型
		$this->upload_path = "/upload/" . date("Ym/d/");
		$this->upload->savePath = public_path($this->upload_path);
		if (!is_dir($this->upload->savePath)) {
			mkdir($this->upload->savePath, 0777, true);
		}
		$res = array();
		if (!$img = $this->upload->uploadOne($_FILES["Filedata"])) {
			$res['errcode'] = 200;
			$res['errmsg'] = $this->upload->getErrorMsg(); //捕获上传异常
		}else {
			$res['title'] = $img[0]["name"];
			$res['filename'] = $this->upload_path . $img[0]["savename"];
			$res['errcode'] = 0;
			$res['errmsg'] = '上传成功';
		}

		exit(json_encode($res));
	}

	function resize() {
		if (!isset($_POST["img"])) {
			$res['errcode'] = 404;
			$res['errmsg'] = "图片不存在";
		}else {
			$image = $this->librarys("Image");
			$x = $_POST["x"];
			$y = $_POST["y"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			$picture = ROOT . $_POST["img"];

			if ($image->crop($picture, $picture, $type = '', $w, $h, $x, $y)) {
				$res['errcode'] = 0;
				$res['errmsg'] = '裁剪成功';
				$res['filename'] = array("small" => $_POST["img"]);
			}else {
				$res['errcode'] = 200;
				$res['errmsg'] = "裁剪失败";
			}
		}
		echo json_encode($res);
	}
	
	function cropper() {
		if(request("filename") != ''){
			$filename = request("filename");
			$image = $this->librarys("Image");
			$x = $_POST["x"];
			$y = $_POST["y"];
			$w = $_POST["width"];
			$h = $_POST["height"];
			$filename = public_path($filename);

			if ($image->crop($filename, $filename, $type = '', $w, $h, $x, $y)) {
				echo success(array('errmsg'=>'裁剪成功','filename'=>request("filename")));
			}else {
				echo fail(array('errmsg'=>'裁剪失败','filename'=>request("filename")));
			}
		}
	}
	
	function remove(){
		if(request("filename") !=''){
			$filename = request("filename");
			if(file_exists(public_path($filename))){
				$result  = unlink(public_path($filename));
				if($result){
					echo success(array('errmsg'=>'删除成功'));
				}else{
					echo fail(array('errmsg'=>'删除失败'));
				}
			}else{
				echo fail(array('errmsg'=>'文件不存在'));
			}
		}
	}
}

?>