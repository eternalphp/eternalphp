<!DOCTYPE HTML>
<html>
<head>
<title>
<?=C("system_name")?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/assets/css/main-min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/plugins/artDialog/skins/default.css?4.1.7">
<script type="text/javascript" src="__PUBLIC__/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/assets/js/bui-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/assets/js/common/main-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/assets/js/config-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<style>
.logo{float:left;margin:10px;}
</style>
</head>
<body>
<div class="header">
	<div class="logo"><img src="__PUBLIC__/images/logo.png" /></div>
    <div class="dl-title"> <?=C("system_name")?> </div>
    <div class="dl-log">欢迎您，<span class="dl-log-user">
        <?=session("username")?>
        </span><a href="javascript:void(0)" url="<?=autolink(array("login","loginOut"))?>" title="退出系统" class="dl-log-quit">[退出]</a> </div>
</div>
<div class="content">
    <div class="dl-main-nav">
        <div class="dl-inform">
            <div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div>
        </div>
        <ul id="J_Nav"  class="nav-list ks-clear">
            <?php
			$xy=1;
			foreach($topMenu as $val){
			?>
            <li class="nav-item dl-selected">
                <div class="nav-item-inner <?php if($xy==1) {?>nav-home<?php }else {?>nav-order<?php }?>">
                    <?=$val["name"]?>
                </div>
            </li>
            <?php
			 $xy++;
			}
			?>
        </ul>
    </div>
    <ul id="J_NavContent" class="dl-tab-conten">
    </ul>
</div>
<script>
    BUI.use('common/main',function(){

      var config=<?=$config?>;

      new PageUtil.MainPage({
        modulesConfig : config
      });
    });
	
	$(function(){
	   $(".dl-log-quit").click(function(){
	      var $this=$(this);
	      artDialog.confirm("确定要退出系统吗",function(){
			   $.ajax({
				 url:$this.attr("url"),
				 type:"GET",
				 dataType:'json',
				 success:function(res){
					if(res.errcode == 0){
					   location.href='<?=autolink(array('login'))?>';
					}else{
					   art.dialog.tips(res.errmsg);
					}
				 }
			   })
		  },function(){
			  art.dialog.tips("您已取消本次操作");
		  })
	   })
	})
  </script>
</body>
</html>
