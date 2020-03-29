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
<script type="text/javascript" src="__PUBLIC__/js/HtmlControl.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/ztree/zTreeStyle.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.excheck-3.5.js"></script>

<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/ueditor.all.js"></script>

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
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
    <tr>
      <td width="15%" class="tableleft"><?=L("system.supparty")?></td>
      <td>
	  	<input name="parentid" fieldname="partyid" type="hidden" treeId="treeDemo" bindHtmlControl="selectDropdown" class="input-xlarge" value=""></td>
    </tr>
	
    <tr>
        <td class="tableleft"><?=L("system.partyname")?></td>
        <td><input name="name" type="text" id="name" class="input-xlarge" placeholder="请输入部门名称" data-required="{required:true}"><label class="tips">*</label></td>
    </tr>
	
    <tr>
        <td class="tableleft"><?=L("base.sort")?></td>
        <td>
            <input name="order" type="number" id="order" class="input-xlarge" max="100" placeholder="请输入整数">
        <label></label></td>
    </tr>
    <tr>
      <td class="tableleft">简介</td>
      <td><textarea name="content" cols="100" rows="5" id="content" style="width:500px;"></textarea></td>
    </tr>
	
    <tr>
        <td class="tableleft">部门总人数</td>
        <td>
            <input name="total" type="text" id="total" class="input-xlarge" placeholder="请部门总人数">
        <label></label></td>
    </tr>
	
    <tr>
        <td class="tableleft">换届起算日期</td>
        <td>
            <input name="startdate" type="text" id="startdate" class="input-xlarge date" placeholder="请输入日期">
        <label></label></td>
    </tr>
	
    <tr>
        <td class="tableleft">到届日期</td>
        <td>
            <input name="enddate" type="text" id="enddate" class="input-xlarge date" placeholder="请输入日期">
        <label></label></td>
    </tr>
	
    <tr>
      <td class="tableleft">是否退休支部</td>
      <td>
	<label class="checkbox inline">
	  <input type="radio" name="retirement" value="1"> 是	</label>
	
	<label class="checkbox inline">
	  <input name="retirement" type="radio" value="0" checked> 
	  否	</label>	  </td>
    </tr>
    <tr>
      <td class="tableleft">是否总支部门</td>
      <td>
	<label class="checkbox inline">
	  <input type="radio" name="ismaster" value="1"> 是	</label>
	
	<label class="checkbox inline">
	  <input name="ismaster" type="radio" value="0" checked> 
	  否	</label>
	  </td>
    </tr>
    <tr class="nums" style="display:none;">
      <td class="tableleft">支部总人数</td>
      <td>
        <input name="nums" type="text" id="nums">
      <label></label></td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary">  <?=L("base.save")?>  </button>        </td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){

	Control.onSubmit();
	
	try{
		Control.UEedit('content');
	}catch(err){}
	
	HtmlControl.selectDropdown();

    setting.check.enable = false;
	var zNodes =<?=$party?>;
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	
	var win = art.dialog.open.origin;
	var nodes = win.getSelectedNodes();
	if(nodes){
		var treeDemo = $.fn.zTree.getZTreeObj("treeDemo");
		var node = treeDemo.getNodesByParam('partyid',nodes["partyid"], null);
		if(node.length>0){
			treeDemo.selectNode(node[0]);
			$(":input[name='displayName']").val(nodes["name"]);
			$(":input[name='parentid']").val(nodes["partyid"]);
		}
	}
	
	$(":input[name='retirement']").click(function(){
		if($(this).val() == 1){
			$(".nums").show();
		}else{
			$(".nums").hide();
			$(":input[name='nums']").val("");
		}
	})
});

</script>
