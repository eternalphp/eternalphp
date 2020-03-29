<?php

namespace App\Admin\Controllers;
use System\Core\Controller;

class loginAction extends Controller {
	
    function __construct() {
        parent::__construct();
        $this->model = $this->models('login');
    }
	
    function index() {
        if ($this->session("username") != '') {
            header("location:" . autolink(array('admin')));
        }
        $this->view('Login/index');
    }
	
    function validate() {
        $this->library('Captcha')->show();
    }
	
    function reload() {
        $this->view('Login/reload');
    }
	
    function chklogin() {
        $this->model->checklogin();
    }
	
    function loginOut() {
        $this->model->loginOut();
    }
}
?>