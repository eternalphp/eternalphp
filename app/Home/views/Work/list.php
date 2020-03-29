
<html>
<head>
    <meta charset="utf-8">
    <title>党务工作</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css">
	
	<link rel="stylesheet" href="__PUBLIC__/weiui/lib/weui.min.css">
	<link rel="stylesheet" href="__PUBLIC__/weiui/css/jquery-weui.css">
	
	<style>
		.watermark{  background:url(<?=U("fontImg",array('name'=>urlencode(session("name"))))?>);}
		.main{ display:none;}
	</style>
</head>
<body>
<main id="app">
<div class="mui-content">

	<div class="tab-control">
		<div class="mui-row">
			<?php if($list) {?>
			<?php foreach($list as $k=>$val) {?>
			<div class="mui-col-xs-5 mui-col-sm-5 text-center tab-column"><a href="<?=U("work",array('newsid'=>$val["newsid"]))?>" class="<?php if($row["newsid"] == $val["newsid"]) {?>active<?php }?>"><?=$val["title"]?></a></div>
			<?php if($k == 0) {?>
			<div class="mui-col-xs-2 mui-col-sm-2">
				<div class="split"></div>
			</div>
			<?php }?>
			<?php }?>
			<?php }?>
		</div>
	</div>

	<div class="container-fluid news">
		
		<div style="height:10px"></div>
		<div class="content" style="background: #ffffff;padding: 10px;border-radius: 10px;">
			<?=$row["content"]?>
		</div>
		
	</div>

	<div style="clear:both;"></div>

</div>
</main>


<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/weiui/lib/fastclick.js"></script>
<script src="__PUBLIC__/weiui/js/jquery-weui.js"></script>
<script src="__PUBLIC__/js/vue.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

<script>
wx.config(<?=json_encode($config)?>);
wx.ready(function(){
	wx.hideOptionMenu();
});

$(function(){

	FastClick.attach(document.body);
	
	$.showLoading();
	
	setTimeout(function() {
	  $(".main").show();
	  $.hideLoading();
	}, 500);
	
})

</script>

</body>
</html>