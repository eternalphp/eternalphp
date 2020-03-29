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


    </style>
</head>
<body class="main">
<form class="form-inline definewidth m20" action="" method="post">
  <?=L("system.rolename")?>：
  <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=request("keyword")?>">
 &nbsp;&nbsp;
  <button type="submit" class="btn btn-primary"> <?=L("base.search")?></button>&nbsp;&nbsp;<button type="button" class="btn btn-success" id="add" name="add"><?=L("base.add")?></button>
</form>
<table width="100%" class="table table-bordered table-hover definewidth m10" >
  <thead>
    <tr>
      <th width="7%"><?=L("base.no")?></th>
      <th width="30%"><?=L("system.rolename")?></th>
      <th width="13%"><?=L("base.status")?></th>
      <th width="17%"><?=L("base.createdate")?></th>
      <th width="19%"><?=L("base.operate")?></th>
    </tr>
  </thead>
  <?php  if ($list) {?>
  <?php
	foreach($list as $k => $val) {
	$menu = array();
	$menu[] = array('url'=>autolink(array(get('class'), "edit", "id" => $val["roleid"])),'name'=>'edit','text'=>L("base.edit"));
	$menu[] = array('url'=>autolink(array(get('class'), "remove", "id" => $val["roleid"])),'name'=>'remove','text'=>L("base.remove"));
  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["rolename"]?></td>
    <td align="center"><?=($val["status"] == 1)?L('base.enable'):L('base.disable')?></td>
    <td align="center"><?=$val["createtime"]?></td>
    <td align="center"><?=menulink($menu)?> </td>
  </tr>
  <?php } ?>
  <?php }else{ ?>
  <tr>
    <td height="30" colspan="7" align="center"><div class="nodata"><?=L("base.nodata")?></div></td>
  </tr>
  <?php } ?>
</table>

<table width="100%" class="definewidth m10">
  <tr>
    <td align="right"><?=L('base.pageTotalCount',$total)?> <?=$pagelink?></td>
  </tr>
</table>

</body>
</html>
<script>
$(function () {
	$("#add").click(function(){
	    artDialog.open('<?=autolink(array(get('class'), "add"))?>',{id:"add",title:'<?=L("role.add")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	$(".edit").click(function(){
	    $(this).css("color","#FF0000");
	    artDialog.open($(this).attr("url"),{id:'edit',title:'<?=L("role.edit")?>',width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
})
</script>
