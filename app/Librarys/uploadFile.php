<?php 

namespace App\Librarys;

class uploadFile {
	
	private $file;
	private $allowExts = array('jpg','jpeg','gif','png','ppt','pdf','xls','xlsx','doc','docx','mp4','mp3');
	private $allowTypes = array();
	private $maxSize = 2*1024*1024; // 2M
	private $filename;
	private $savePath;
	private $error;
	
	public function __construct($file){
		$this->file = $file;
		return $this;
	}
	
	public function getClientName(){
		return $this->file['name'];
	}
	
	public function getClientExtension(){
		$fileExts = explode(".",$this->file['name']);
		return strtolower($fileExts[1]);
	}
	
	public function getClientSize(){
		return $this->file['size'];
	}
	
	public function getFilename(){
		return $this->file['tmp_name'];
	}
	
	public function getClientType(){
		return strtolower($this->file['type']);
	}
	
	public function savePath($path){
		if(!file_exists($path)){
			mkdir($path,0777,true);
		}
		$this->savePath = $path;
		return $this;
	}
	
	public function fileName($name = null){
		if($name == null){
			$name = sprintf("%s.%s",uniqid(),$this->getClientType());
		}
		$this->filename = rtrim($this->savePath,'/') . $name;
		return $this;
	}
	
	public function allowExts($fileExts = array()){
		$this->allowExts = $fileExts;
		return $this;
	}
	
	public function allowTypes($types = array()){
		$this->allowTypes = $types;
		return $this;
	}
	
	public function getErrorNo(){
		return $this->file['error'];
	}
	
	public function error()
	{	
		$errorNo = $this->getErrorNo();
		if($errorNo == 0){
			return true;
		}
		
		switch($errorNo) {
			case 1:
				$this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
				break;
			case 2:
				$this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
				break;
			case 3:
				$this->error = '文件只有部分被上传';
				break;
			case 4:
				$this->error = '没有文件被上传';
				break;
			case 6:
				$this->error = '找不到临时文件夹';
				break;
			case 7:
				$this->error = '文件写入失败';
				break;
		}
		return false;
	}
	
	public function isValid(){
		
		if(!$this->error()){
			return false;
		}
		
		//文件类型
		if($this->allowExts && !in_array($this->getClientExtension(),$this->allowExts)){
			$this->error ='上传文件类型不允许';
			return false;
		}
		//文件后缀名
		if($this->allowTypes && !in_array($this->getClientType(),$this->allowTypes)){
			$this->error = '上传文件MIME类型不允许！'.$this->getClientType();
			return false;
		}
		//文件大小
		if($this->getClientSize() > $this->maxSize){
			$this->error = '上传文件大小不符！';
			return false;
		}
		
		if(!is_uploaded_file($this->getFilename())){
			$this->error = '非法上传文件！';
			return false;
		}
		
		return true;
	}
	
	public function putFile($filename = null){
		
		if($filename != null){
			$this->filename = $filename;
		}
		
		if(file_exists($this->filename)){
			$this->error = '文件已存在！';
			return false;
		}
		
		$savePath = dirname($this->filename);
		if(!file_exists($savePath)){
			if(!mkdir($savePath,0777,true)){
				$this->error = '文件目录创建失败！';
				return false;
			}
		}
		
		if(!is_writable($savePath)){
			$this->error = '文件目录没有写入权限！';
			return false;
		}
		
		if(!move_uploaded_file($this->getFilename(), $this->filename)) {
			$this->error = '文件上传保存错误！';
			return false;
		}
		
		return true;
	}
	
	public function getError(){
		return $this->error;
	}
}
?>