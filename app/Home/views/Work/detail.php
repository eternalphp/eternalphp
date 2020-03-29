
<html>
<head>
    <meta charset="utf-8">
    <title><?=$row["title"]?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css?t=<?=time()?>">
	<style>
		.watermark{  background:url(<?=U("fontImg",array('name'=>urlencode(session("name"))))?>);}
	</style>
</head>
<body>
<main id="app">
    <div class="focus-news-detail watermark">
	
        <div class="mui-table-view">

			<div style="padding: 10px 10px;">
				<div id="segmentedControl" class="mui-segmented-control">
					<?php if($list) {?>
					<?php foreach($list as $k=>$val) {?>
					<a class="mui-control-item <?php if($row["newsid"] == $val["newsid"]) {?>mui-active<?php }?>" href="<?=U("work",array('newsid'=>$val["newsid"]))?>">
						<?=$val["title"]?>
					</a>
					<?php }?>
					<?php }?>
				</div>
			</div>
			
        </div>
		
	
        <div class="container-fluid news">
			
			<div style="height:10px"></div>
			<div class="content" style="background: #ffffff;padding: 10px;border-radius: 10px;">
				<?=$row["content"]?>
			</div>
			
        </div>

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
</script>
</body>
</html>