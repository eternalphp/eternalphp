<?php

namespace App\Home\Models;
use App\Librarys\SLSLog;

class suggestModel extends BaseModel {
	
	public function __construct(SLSLog $SLSLog) {
		parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('意见建议');
	}
	
	function save(){
		
		$token = request("token");
		if(empty($token) || $token != session("token")){
			echo fail('非法提交');
			exit;
		}

		$_POST["userid"] = $this->session("userid");
		$_POST["createtime"] = date("Y-m-d H:i:s");
		
		$_POST["content"] = format_text($_POST["content"]);
		if(empty($_POST["content"])){
			echo fail('请输入内容');
			exit;
		}
		
		$files = array();
		if(isset($_POST["file"])){
			
			foreach($_POST["file"] as $base64_image_content){
				$filename = $this->uploadBase64File($base64_image_content);
				if($filename){
					$files[] = $filename;
				}
			}
		}
		
		if($files){
			$_POST["filename"] = implode("|",$files);
		}
		
		$newsid = $this->model->table("suggest")->insert($_POST);
		if($newsid > 0){
			$this->session("token",null);
			echo success('提交成功');
		}else{
			echo fail('提交失败');
		}
		$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
		
	}
}

?>