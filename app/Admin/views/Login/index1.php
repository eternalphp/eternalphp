<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>后台登陆</title>
<style type="text/css"> 
*,body{margin:0px; padding:0px; font-size:12px;}
img,tbody,th,table,td,div,ul,li,p,span,dl,dt,dd,frame,h1,h2,h3,h4,h5{margin:0px; padding:0px; border:0px;font-size:12px;font-family:"宋体",Arial, Helvetica, sans-serif; line-height:normal; color:#000000;font-weight:normal;}
body{margin:0px; padding:0px;height:100%; overflow: hidden;background:#367FA5 url(<?=TEMP_PATH?>/images/bg.jpg) right 0px  no-repeat;}
p{line-height:180%; vertical-align:middle; padding:5px 0px;}
img{border:0px;}a{color:#FFF; text-decoration:none;}
#login_page{height:auto; clear:both; width:100%; clear:both; z-index:100;}
#ct_all{width:550px; height:100px; position: relative; top:250px; left:250px; clear:both; display: block; z-index:1000;}
#bar_left{width:226px; height:115px; float:left;margin-right:10px;}
#foot{position:relative; text-align:center; color:#88D2F4; line-height:30px; top:380px;clear:both; display:block; z-index:10;}
#foot a,#foot a:hover{color:#88D2F4; font-family:Verdana, Geneva, sans-serif; font-size:12px;}
#pannel em{font-style:normal; color:#FFF;}
.input_set{border:1px solid #FFF;height:20px; line-height:20px; color:#000; width:127px;}
.read_input{border:1px solid #369;}
#login_btn{text-align:center; width:550px; clear:both; position: absolute; top:130px;}
#login_btn input{background:url(<?=TEMP_PATH?>/images/btn.jpg) left top no-repeat; width:67px; height:26px; border:none; color:#000;}
#login_btn span{margin-left:20px;}
#ajaxCallMsg{position: absolute; left:150px;top:147px;color:#FFF;border:1px solid #fff;text-align:center;padding:8px; margin:0px auto;display:none;width:580px;text-align:left;}
#ajaxCallMsg font{color: #FFF;}
#cap_imgs{margin-bottom:-8px;cursor:pointer;}
</style>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.js"></script>
<script language="JavaScript">
var excption = "非法操作.或数据异常.请清除您电脑的cookies再试.";var login = "登　陆";</script>
<script type="text/javascript"> 
function checkForm(){
	var result = true;
	$(".must_fill_in").each(function(i){
		var val = $.trim($(this).val());
		if(val ==''){$(this).addClass('read_input');result = false;}else{$(this).removeClass('read_input');}
	});
	return result;
}
function _login(){
	if(!checkForm())return false;
	var n = $("#name").val();var p = $("#pass").val();var c = $("#cap").size();var opt = {};
	opt = $("#cap").size()>0?{username:n,password:p,vcode:$("#cap").val()}:{username:n,password:p};
	$.post('<?=autolink(array("login","chklogin"))?>',opt,function(data){

			var status=eval("("+data+")");
			if(status['status']=='ok'){
			   window.location.href='<?=autolink(array("admin"))?>';
			}else if(status['status']=='fail'){
			   var error=status['msg'];
			   if(error.indexOf("用户名")!=-1){
			      $("#name").focus();
				  $("#name").val("");
			   }else if(error.indexOf("密码")!=-1){
			      $("#pass").focus();
				  $("#pass").val("");
			   }else{
			      $("#cap").focus();
				  $("#cap").val("");
			   }
			   alert(error);  
			}
	});
}
function _resize(){
	var d = $("#ajaxCallMsg");
	if($(d).length){
		var left = parseFloat(($(this).width()-$(d).width())/2)
		$(d).css({"left":left+'px'});
	}
	var cal_main = $("#ct_all");
	$(cal_main).css({"left":parseFloat(($(this).width()-$(cal_main).width())/2)+'px'});
	if(!$(".ecap_pannel").length){
		$("#pannel").css({"padding-top":"20px"});	
	}
	if(window.screen.width>1024){
		$("#foot").css({"top":"380px"});	
	}	
}
$(function(){
	$("#cap").focus(function(){_show_code();});
	$("#login_form").submit(function(){
		_login();return false;	
	});
	_resize();
	$(this).resize(function(){
		_resize();
	});
});

function _show_code(){
  
  var path="<?=autolink(array("login","validate"))?>";
  if(path.indexOf('?')!=-1){
    path=path+'&rand='+Math.random();
  }else{
    path=path+'/rand/'+Math.random();
  }
  $("#cap_imgs").attr({"src":path});

}

  function correctPNG(){
		for(var i=0; i<document.images.length; i++)
		{
		var img = document.images[i];
		var imgName = img.src.toUpperCase();
			if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
			{
				var imgID = (img.id) ? "id='" + img.id + "' " : "";
				var imgClass = (img.className) ? "class='" + img.className + "' " : "";
				var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
				var imgStyle = "display:inline-block;" + img.style.cssText;
				if (img.align == "left") imgStyle = "float:left;" + imgStyle;
				if (img.align == "right") imgStyle = "float:right;" + imgStyle;
				if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
				var strNewHTML = "<span "+ imgID + imgClass + imgTitle + "style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";" 
				+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader" + "(src='" + img.src + "', sizingMethod='scale');\"></span>";
				img.outerHTML = strNewHTML;
				i = i-1;
			}
		}
    }
	var isIE=!!window.ActiveXObject; 
	var isIE6=isIE&&!window.XMLHttpRequest;
	if(isIE6){window.attachEvent("onload", correctPNG);} 	
</script>
</head>
<body scroll="no">
<form method="post" action="" id="login_form" autocomplete="off">
<div id="login_page">
<div id="ajaxCallMsg" style="display:none;">您还有4次登陆机会,5次机会一过您将在15分钟后才能登陆.<br />
  <br /><b>错误提示:</b><font>用户名或密码错误或该用户已被禁用.</font></div>
<div id="ct_all">
	<div id="bar_left"><img src="<?=TEMP_PATH?>/images/bar1.png" /></div>
	<div id="pannel">
		<p><em>账&nbsp;&nbsp;号：</em><span><input class="input_set must_fill_in" type="text" value="" id="name"  name="cp_name" maxlength="30"/></span></p>
		<p><em>密&nbsp;&nbsp;码：</em><span><input class="input_set must_fill_in" type="password" id="pass" value="" name="cp_passwords" maxlength="40" /></span></p>
		<p class="ecap_pannel"><em>验 证 码：</em><span><input type="text" class="input_set must_fill_in" name="cp_captcha" value="" id="cap" maxlength="4" /></span><span id="cap_img">
        <img src="<?=autolink(array("login","validate"))?>"  id="cap_imgs" onclick="_show_code();"/></span></p>    </div>
     <div id="login_btn" class="btn"><span><input type="submit"  onfocus="this.blur();" class="btn" value="登&nbsp;陆" /></span><span><input type="reset"  class="btn" value="取&nbsp;消"  onfocus="this.blur();" /></span></div>
<div class="clear"></div>
</div>
<div class="bar" id="foot"> &copy;2011-2021 后台管理系统,并保留所有权利</div>
<div class="clear"></div>
</div>
</form>
</body>
</html>
