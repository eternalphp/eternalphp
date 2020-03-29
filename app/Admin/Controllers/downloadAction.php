<?php

namespace App\Admin\Controllers;

class downloadAction extends Controller {
    function __construct() {
        parent::__construct();
    }
    function index() {
        $filename = ROOT . ltrim(get("filename"), '/');
        $fileinfo = pathinfo($filename);
        header('Content-type: application/x-' . $fileinfo['extension']);
        header('Content-Disposition: attachment; filename=' . $fileinfo['basename']);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit();
    }
}
?>