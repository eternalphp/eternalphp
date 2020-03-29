<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/common.css">
	<link rel="stylesheet" href="__PUBLIC__/validator/validator.css">
</head>
<body>
<main>
	<form id="form1" action="/login/verifyLogin" method="post" target="_self">
    <div class="register" style="margin-top:30%;">
        <div class="mui-input-group mui-table-view">
            <div class="mui-table-view-cell mui-input-row">
                <label>帐号</label>
                <input name="username" type="text" placeholder="请输入帐号" data-required="{required:true}">
            </div>
            <div class="mui-table-view-cell mui-input-row">
                <label>密码</label>
                <input name="password" type="text" placeholder="请输入密码" data-required="{required:true}">
            </div>
            <a class="btn-register" id="submit" href="javascript:void(0)"> 登录 </a>
        </div>
    </div>
	</form>
</main>
<script src="__PUBLIC__/js/jquery-2.1.0.min.js"></script>
<script src="__PUBLIC__/js/mui.min.js"></script>
<script src="__PUBLIC__/validator/jquery.validator.js"></script>
<script>
$(function(){

	$("#submit").click(function(){
		if($("#form1").validateData()){
			$("#form1").submit();
		}
	})
	
})

</script>
</body>
</html>