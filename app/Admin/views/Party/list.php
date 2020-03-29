<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-store" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/ztree/zTreeStyle.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.excheck-3.5.js"></script>

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

<div class="container-fluid">
  <div class="row-fluid">
    <div style="width:218px; float:left;">
		<table width="100%" class="table table-bordered m10">
		<tr>
		   <td height="400" valign="top">
			 <ul class="ztree" id="treeDemo" style="width:200px;max-height:600px;overflow:hidden;overflow-y:auto;overflow-x:auto; padding-bottom:40px;"></ul>
	      </td>
		</tr>
		</table>
    </div>
    <div style="margin-left:238px; position:relative;" id="PagePannel">	
	
 <form class="form-inline definewidth m20" action="" method="post">
     <?=L("system.partyname")?>：<input type="text" name="keyword" id="keyword" class="input-default" value="<?=request("keyword")?>">
    &nbsp;&nbsp;<button type="submit" class="btn btn-primary"><?=L("base.search")?></button>
    <button type="button" class="btn btn-success" id="add" name="add"><?=L("base.add")?></button>
 </form>

 <table width="100%" class="table table-bordered table-hover definewidth m10 table-striped">
  <thead>
    <tr>
      <th width="10%" align="center"><?=L("base.no")?></th>
      <th width="29%" align="center"><?=L("system.partyname")?></th>
      <th width="29%" align="center"><?=L("system.supparty")?></th>
	  <th width="15%" align="center">是否退休支部</th>
	  <th width="17%" align="center"><?=L("base.operate")?></th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){
		
	  $item = array();
	  $item[] = array('url'=>autolink(array("party","edit","partyid"=>$val["partyid"])),'name'=>'edit','text'=>L("base.edit"));
	  $item[] = array('url'=>autolink(array("party","remove","partyid"=>$val["partyid"])),'name'=>'remove','text'=>L("base.remove"));

  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><?=$val["parentname"]?></td>
    <td align="center"><?=($val["retirement"] == 1)?"是":"否"?></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  <?php }?>
  <?php }else {?>
  <tr>
    <td height="30" colspan="8" align="center"><div class="nodata"><?=L("base.nodata")?></div></td>
  </tr>
  <?php }?>
</table>

<table width="100%" class="definewidth m10">
  <tr>
    <td align="right"><?=L("base.pageTotalCount",$total)?> <?=$pagelink?></td>
  </tr>
</table>

		
    </div>
  </div>
</div>


 
</body>
</html>

<script>
$(function () {
    
	
	//新增
	$("#add").click(function(){
	    artDialog.open("<?=autolink(array('party','add'))?>",{id:'add',title:"添加部门",width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	//编辑
	$(".edit").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'edit',title:"编辑部门",width:"80%",height:"90%",opacity:0.4}).lock();
	})
	

	
	Control.remove();
	
	var ajaxUrl = "<?=autolink(array('party','index','parentid'=>'{:parentid}'))?>";
	setting.check.enable = false;
	setting.callback = {
		onClick:function(event, treeId, treeNode){
			var url = ajaxUrl.replace('{:parentid}',treeNode.id);
			location.href = url;
		}
	}
	var partyJson=<?=$partyJson?>;
	$.fn.zTree.init($("#treeDemo"), setting,partyJson);
	
	var partyid = "<?=request("parentid")?>";
	var treeDemo = $.fn.zTree.getZTreeObj("treeDemo");
	var node = treeDemo.getNodesByParam('partyid',partyid, null);
	if(node.length>0){
		treeDemo.selectNode(node[0]);
	}
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);

})

function getSelectedNodes(){
	var treeDemo = $.fn.zTree.getZTreeObj("treeDemo");
	var nodes = treeDemo.getSelectedNodes();
	if(nodes.length>0){
		return nodes[0];
	}else{
		return null;
	}
}
</script>