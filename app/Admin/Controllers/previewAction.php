<?php

namespace App\Admin\Controllers;

class previewAction extends  Controller{
 
	function __construct(){
		parent::__construct();
	}
   
	function index(){
		if(request("filename")!=''){
			$data["filename"] = HOME_PATH.request("filename");
			$this->view('Preview/view',$data);
		}
		
	}
}
?>