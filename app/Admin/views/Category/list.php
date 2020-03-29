<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
		.face{display: inline-block;width: 30px;height: 30px;margin: 0 6px 0 2px;vertical-align: middle;border: 1px solid #cdcdcd;border-radius: 2px;}
    </style>
</head>
<body>
<form class="form-inline definewidth m20" action="" method="post">
    &nbsp;&nbsp;标题：
    <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=request("keyword")?>">
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">搜索</button>
    <button type="button" class="btn btn-success" id="addnew" name="add">添加</button>
</form>
<table width="100%" class="table table-bordered table-hover definewidth m10">
    <thead>
        <tr>
            <th width="10%" align="center">序号</th>
            <th width="54%" align="left">分类标题</th>
            <th width="20%" align="center">图片</th>
            <th width="16%" align="center">操作</th>
        </tr>
    </thead>
    <?php
    if($list){
       foreach($list as $k=>$val){
	   	   $item = array();
		   if($val["catid"]>1) $item[] = array('url'=>autolink(array(get('class'),"remove","catid"=>$val["catid"])),'name'=>'remove','text'=>'删除');
   ?>
    <tr class="listview">
        <td align="center"><?=$k+1?></td>
        <td><?=$val["title"]?></td>
        <td align="center"><img src="<?=($val["picture"] != '')?$val["picture"]:'__PUBLIC__/images/avatar_default.png'?>" class="face" /></td>
        <td align="center"><?=menulink($item)?></td>
    </tr>
	<?php }?>
	<?php }else {?>
    <tr>
        <td height="30" colspan="4" align="center"><div class="nodata">没有数据……</div></td>
    </tr>
    <?php } ?>
</table>
<table width="100%" class="definewidth m10">
    <tr>
        <td align="right">总计数<?=$total?>条<?=$pagelink?></td>
    </tr>
</table>
</body>
</html>
<script>
$(function () {
	//新增
	$("#addnew").click(function(){
	     artDialog.open("<?=autolink(array(get('class'),'add'))?>",{id:'add',title:"新增分类",width:"80%",height:"70%"}).lock();
	});
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
	menuAction.push('remind');
	menuAction.push('copy');
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
})
 
</script>
