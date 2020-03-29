<?php

namespace App\Admin\Controllers;

class adminAction extends CommonAction {
	
    function __construct() {
        parent::__construct();
        $this->models = $this->models('admin');
    }
	
    function index() {
        $config = $this->models("menu")->getAuthMenu();
        $data["config"] = urldecode(json_encode($config));
        $data["topMenu"] = $this->models("menu")->getTopMenu();
        $this->view('index', $data);
    }
}
?>