<?php

namespace App\Home\Controllers;
use System\Core\Controller;
use App\Librarys\SFTPConnection;

class ftpAction extends Controller {

	function __construct() {
		parent::__construct();
	}
	
	function ftp(){
		$sftp = new SFTPConnection("15.75.21.33", 22);
		$sftp->login("root", "1Qaz2wsx@");
		$sftp->uploadFile("/var/www/html/ganghang/ftp.txt", "/var/www/html/ganghang/");
	}

}

?>