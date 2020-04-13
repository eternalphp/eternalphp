<?php

namespace App\Admin\Controllers;

class adminAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
    }
	
    function index() {
		
		$data = array();
        $this->view('index', $data);
    }
}
?>