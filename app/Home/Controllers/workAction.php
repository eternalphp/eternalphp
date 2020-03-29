<?php

namespace App\Home\Controllers;
use App\Librarys\SLSLog;

class workAction extends CommonAction {

	function __construct(SLSLog $SLSLog) {
		parent::__construct();
		$this->model = $this->models("work");
		$this->signPackage = $this->weixinApi->getSignPackage();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('党务工作');
		$this->model->onQuery();
	}
	
	function detail(){
		$this->checkLogin();
		$data["list"] = $this->model->index();
		$data["row"] = $this->model->getRow();
		$data["config"] = $this->signPackage;
		if(!$data["row"] || $data["row"]["isdelete"] == 1){
			$this->view("Error/message");
			exit;
		}
		$this->view("Work/list",$data);
		$this->SLSLog->query($this->model->getQuerys())->where($this->model->getConditions())->write();
	}

}

?>
