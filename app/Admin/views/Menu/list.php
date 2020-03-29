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
        body,html {
            padding-bottom: 40px;
			font-family: "ff-tisa-web-pro-1","ff-tisa-web-pro-2","Lucida Grande","Helvetica Neue",Helvetica,Arial,"Hiragino Sans GB","Hiragino Sans GB W3","Microsoft YaHei UI","Microsoft YaHei","WenQuanYi Micro Hei",sans-serif;
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

    </style>
</head>
<body class="main">

 <form class="form-inline definewidth m20" action="" method="post">
  &nbsp;&nbsp;<?=L("base.keyword")?>：
  <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=request("keyword")?>">
  &nbsp;&nbsp;
  <button type="submit" class="btn btn-primary"><?=L("base.search")?></button>
  &nbsp;&nbsp;<button type="button" class="btn btn-success" id="add" name="add"><?=L("base.add")?></button>
 </form>

<table width="100%" class="table table-bordered table-hover definewidth m10 dataview">
    <tr>
      <th width="66%" align="left"><?=L("system.menuname")?></th>
      <th width="16%" align="center"><?=L("base.ishidden")?></th>
      <th width="18%" align="center"><?=L("base.operate")?></th>
    </tr>

  <?php
   if($list){
    foreach($list as $k=>$val){
		
	  $item = array();
	  $item[] = array('url'=>autolink(array("menu","edit","menuid"=>$val["menuid"])),'name'=>'edit','text'=>L("base.edit"));
	  $item[] = array('url'=>autolink(array("menu","remove","menuid"=>$val["menuid"])),'name'=>'remove','text'=>L("base.remove"));

  ?>
  <tr class="listview">
    <td><?=$val["name"]?></td>
    <td align="center"><?=($val["hidden"])?'<font color="red">'.L("base.hidden").'</font>':L("base.show")?></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  	  
	  <?php if($val["menus"]) {?>
	  <?php foreach($val["menus"] as $res) {?>
		  <?php
			  $item = array();
			  $item[] = array('url'=>autolink(array("menu","edit","menuid"=>$res["menuid"])),'name'=>'edit','text'=>L("base.edit"));
			  $item[] = array('url'=>autolink(array("menu","remove","menuid"=>$res["menuid"])),'name'=>'remove','text'=>L("base.remove"));
		  ?>
	  
	  <tr class="listview">
		<td style="text-indent:30px;"><?=$res["name"]?></td>
		<td align="center"><?=($res["hidden"])?'<font color="red">'.L("base.hidden").'</font>':L("base.show")?></td>
		<td align="center"><?=menulink($item)?></td>
	  </tr>
	  
		  <?php if(isset($res["items"])) {?>
			  <?php foreach($res["items"] as $rss) {?>
			  
			  <?php
				  $item = array();
				  $item[] = array('url'=>autolink(array("menu","edit","itemid"=>$rss["itemid"])),'name'=>'edit','text'=>L("base.edit"));
				  $item[] = array('url'=>autolink(array("menu","remove","itemid"=>$rss["itemid"])),'name'=>'remove','text'=>L("base.remove"));
			  ?>
			  
			  <tr class="listview">
				<td style="text-indent:60px;"><?=$rss["name"]?></td>
				<td align="center"><?=($rss["hidden"])?'<font color="red">'.L("base.hidden").'</font>':L("base.show")?></td>
				<td align="center"><?=menulink($item)?></td>
			  </tr>
			  <?php } ?>
		  <?php } ?>
	 <?php } ?>
	 <?php } ?>
	 
  <?php } ?>
  <?php }else{ ?>
  <tr>
    <td height="30" colspan="5" align="center"><div class="nodata"><?=L("base.nodata")?></div></td>
  </tr>
<?php } ?>	
</table>
</body>
</html>
<script>
$(function () {

	$("#add").click(function(){
		artDialog.open("<?=autolink(array('menu','add'))?>",{title:'<?=L("menu.add")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	$(".add").click(function(){
		artDialog.open($(this).attr("url"),{title:'<?=L("menu.addSub")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	$(".edit").click(function(){
		$(this).css("color","red");
	    artDialog.open($(this).attr("url"),{title:'<?=L("menu.edit")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	$(".view").click(function(){
		$(this).css("color","red");
	    artDialog.open($(this).attr("url"),{title:'<?=L("manage.submenu")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
	
})
</script>
