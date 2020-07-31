<?php

/**
 * ResponseService
 * @author yuanzhongyi
 * @date 2020-07-31
 */

namespace App\Admin\Services;

use App\Admin\Models\Role;

class ResponseService {
	
	private $errcode = 0;
	private $errmsg = 'success';
	private $data = array();
	
    function __construct() {
    }
	
	function success($errmsg = 'success',$data = array()){
		$this->errcode = 0;
		$this->errmsg = $errmsg;
		$res = $this->toArray();
		$res["data"] = $data;
		echo json_encode($res);
	}
	
	function fail($errcode = 200,$errmsg = 'fail'){
		$this->errcode = $errcode;
		$this->errmsg = $errmsg;
		$res = $this->toArray();
		echo json_encode($res);
	}
	
	function sendJson($data = array()){
		
		$res = $this->toArray();
		$res = array_merge($res,$data);
		echo json_encode($res);
	}
	
	function toArray(){
		return array(
			'errcode'=>$this->errcode,
			'errmsg'=>$this->errmsg
		);
	}
}
?>