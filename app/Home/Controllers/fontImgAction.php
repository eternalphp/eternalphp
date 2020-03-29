<?php 

namespace App\Home\Controllers;

class fontImgAction extends CommonAction{
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		
		if(request("name") != ''){
			$name = request("name");
			Header("Content-type: image/png"); 
			$text = $this->library("textPNG");
			$text->msg = urldecode($name); // 需要显示的文字 
			$text->font = public_path("/themes/fonts/STXIHEI.TTF"); // 字体 
			$text->size = 40; // 文字大小 
			$text->rot = 50; // 旋转角度 
			if (isset($pad)) $text->pad = $pad; // padding
			
			$text->red = 248; // 文字颜色 
			$text->grn = 247; // 
			$text->blu = 248; // 
			$text->bg_red = 255; // 背景颜色. 
			$text->bg_grn = 255; // 
			$text->bg_blu = 255; //  
			$text->transparent = false; // 透明度 (boolean). 
			$text->draw();
		}
 
	}
}
?>