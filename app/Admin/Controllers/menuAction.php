<?php

namespace App\Admin\Controllers;

class menuAction extends CommonAction {

	function __construct() {
		parent::__construct();
	}
	
	function index(){
		$data = array();
		$this->view('index',$data);
	}
	
	function add(){
		$data = array();
		$this->view('add',$data);
	}
	
	function edit(){

	}
	
	function detail(){
		$data = array();
		$this->view('detail',$data);
	}
	
	function save(){

	}
	
	function remove(){

	}
}


?>