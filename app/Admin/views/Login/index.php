<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登陆</title>
<style type="text/css"> 
*,body{margin:0px; padding:0px; font-size:12px;}
img,tbody,th,table,td,div,ul,li,p,span,dl,dt,dd,frame,h1,h2,h3,h4,h5{margin:0px; padding:0px; border:0px;font-size:12px;font-family:"宋体",Arial, Helvetica, sans-serif; line-height:normal; color:#000000;font-weight:normal;}
body{margin:0px; padding:0px;height:100%; overflow: hidden;background:#0683B1  no-repeat;;background-attachment:fixed;background-repeat:no-repeat;background-size:cover;-moz-background-size:cover;-webkit-background-size:cover; }
p{line-height:180%; vertical-align:middle; padding:5px 0px;}
img{border:0px;}a{color:#FFF; text-decoration:none;}

.login{ width:632px; height:300px; margin:0px auto; background:url(__PUBLIC__/images/login.png) no-repeat; padding-top:200px;}
.login .form{ width:315px; height:135px; margin:0px auto;}
.login .form ul{ padding:0px; margin:0px;}
.login .form ul li{ height:46px; line-height:46px; margin:10px auto; list-style:none; display:block; font-size:16px;font-family: Microsoft YaHei;color: white; font-weight:bold;}

.form-control {
    width: 60%;
    height: 34px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.submit{ width:90px;height:37px;background:url(__PUBLIC__/images/anniu1.png);border:none;}
.reset{ width:90px;height:37px;background:url(__PUBLIC__/images/anniu2.png);border:none;}

</style>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>

</head>
<body scroll="no">
<div class="pannelBox" style="width:600px; height:460px; position:absolute; top:30%; left:35%;">
<form method="post" action="" id="login_form" autocomplete="off">
<div class="login">
    <div class="form">
	   <ul>
	      <li style="text-align:center;">账&nbsp;&nbsp;号：&nbsp;&nbsp;<input class="form-control" type="text" value="" id="username"  name="username" maxlength="30"/></li>
		  <li style="text-align:center;">密&nbsp;&nbsp;码：&nbsp;&nbsp;<input class="form-control" type="password" id="password" value="" name="password" maxlength="40" /></li>
		  <li style="text-align:center;"> <input name=" " type="button" class="submit" value=" " />  
		  &nbsp;&nbsp; <input name="" class="reset" type="reset" value=" " /> </li>
	   </ul>
	</div>
</div>
</form>
</div>
</body>
</html>
<script>
$(function(){
   $(".submit").click(function(){
      _login();
   })
   
   $("body").keydown(function() {
	 if (event.keyCode == "13") {
		_login();
	 }
   });
   
   var width=$(window).width();
   var height=$(window).height();
   
   var pwidth=$(".pannelBox").width();
   var pheight=$(".pannelBox").height();
   var left=((width-pwidth)/2)/width*100+'%';
   var top=((height-pheight)/2)/height*100+'%';
   $(".pannelBox").css("left",left);
   $(".pannelBox").css("top",top);
   
})

function _login(){
    var username=$(":input[name='username']").val();
	var password=$(":input[name='password']").val();
	if(username==''){
	   alert("帐户名不能为空");
	   $(":input[name='username']").focus();
	   return false;
	}
	if(password==''){
	   alert("密码不能为空");
	   $(":input[name='password']").focus();
	   return false;
	}
	var opt = {username:username,password:password};
	
	$.post('<?=autolink(array("login","chklogin"))?>',opt,function(data){

		var status=eval("("+data+")");
		if(status['errcode'] == 0){
		   window.location.href = '<?=autolink(array("index"))?>';
		}else if(status['errcode'] > 0 ){
		   var error = status['errmsg'];
		   if(error.indexOf("用户名")!=-1){
			  $(":input[name='username']").focus();
			  $(":input[name='username']").val("");
		   }else if(error.indexOf("密码")!=-1){
			  $(":input[name='password']").focus();
			  $(":input[name='password']").val("");
		   }
		   alert(error);  
		}
	});
}
</script>