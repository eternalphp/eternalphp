<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Menu;

class adminAction extends CommonAction {
	
    function __construct(Menu $model) {
        parent::__construct();
		$this->view->realtime();
		$this->model = $model;
    }
	
    function index() {
		
		$data = array();
		$data["menus"] = $this->model->index()->getAuthMenu();
        $this->view('index', $data);
    }
}
?>