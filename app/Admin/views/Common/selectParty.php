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

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

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


        .role{ height:30px; line-height:30px;}
		.partyList{height:430px; overflow:hidden;overflow-y:auto;}
		.partyList ul{ padding:0px; margin:0px;}
		.partyList ul li{ list-style:none; height:30px; line-height:30px; padding:0px 5px; cursor:pointer; position:relative;}
		.partyList ul li:hover{ background-color:#F8F8F8;}
		
		.icon-remove{background: url(__PUBLIC__/plugins/ztree/img/close-txt-s.png) no-repeat 0px 0px; width:12px; height:12px; position:absolute;top:8px; right:5px;}
		.jstree-custom-checked {
			position: relative;
			width: 12px;
			height: 12px;
			vertical-align: -2px;
			margin-left: 2px;
			display: none;
			background-image: url(__PUBLIC__/plugins/ztree/img/ztree-checked.png);
			background-size: 12px 12px
		}
		
		.jstree-custom-checked.selected {
			display: inline-block
		}
		
		.jstree-wholerow {
			box-sizing: border-box;
			width: 100%;
			height: 26px;
			position: absolute;
			left: 0;
			user-select: none;
			cursor: pointer
		}
    </style>
</head>
<body>
<form id="form1" action="" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered m10">
	
    <tr>
      <td >
		<div class="searchPannel">
		  <input class="span2" name="keyword" placeholder="请输入关键词" type="text" style="margin-bottom:0px; display:inline;width:80%;">
		  <button type="button" name="search" class="btn"> 搜索 </button>
		</div>	  </td>
      <td >已选择部门：</td>
    </tr>
    <tr>
      <td width="50%" >

		<div style=" height:430px; overflow:hidden;overflow-y:auto;">
          <ul id="treeParty" class="ztree">
          </ul>
      </div>
	  <input name="partyids" id="partyids" type="hidden" class="input-large" value="">	  </td>
      <td width="50%" valign="top" >
	  <div class="partyList">
	  	<ul>
		</ul>
	  </div>	  </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
	  <button type="button" name="Submit" class="btn btn-primary"> 确定 </button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){

	var win = art.dialog.open.origin;//来源页面
	
	$(":input[name='Submit']").click(function(){
	
		var nodes = getSelectPartyJson();
		if(nodes.length>0){
			win.selectParty(nodes);
			Control.close("selectParty");
		}else{
			Control.message("请选择部门！");
			return false;
		}
	})
	
	setting.check.enable = false;
	setting.check.chkboxType = {"Y":"","N":""};
	setting.view = {
		addDiyDom: function(treeId, treeNode){
			partyids = win.getSelectParty();
			
			var aObj = $('#' + treeNode.tId + '_a');
			var selectedStr = '<div class="jstree-custom-checked"></div>';
			aObj.append(selectedStr);
			
			if($.inArray(treeNode.partyid,partyids) != -1){
				aObj.find(".jstree-custom-checked").addClass("selected");
				$(".partyList ul").append('<li data-partyid="'+treeNode.partyid+'" title="'+treeNode.name+'"> <i class="icon-file"></i>  '+treeNode.name+'  <i class="icon-remove"></i> </li>');
			}
		}
	};
	setting.callback = {
		onClick:function(event, treeId, treeNode){
			var partyids = getSelectParty();
			var aObj = $('#' + treeNode.tId + '_a');
			var selectedStr = '<div class="jstree-custom-checked"></div>';
			if($.inArray(treeNode.partyid,partyids) == -1){
				aObj.find(".jstree-custom-checked").addClass("selected");
				$(".partyList ul").append('<li data-partyid="'+treeNode.partyid+'" title="'+treeNode.name+'"> <i class="icon-file"></i>  '+treeNode.name+'  <i class="icon-remove"></i> </li>');
			}else{
				var aObj = $('#' + treeNode.tId + '_a');
				aObj.find(".jstree-custom-checked").removeClass("selected");
				removeParty(treeNode.partyid);
			}
		}
	};
	var partyAll = <?=$partyAll?>;
	$.fn.zTree.init($("#treeParty"), setting,partyAll);
	
	function getSelectParty(){
		var partyids = [];
		$(".partyList li").each(function(){
			partyids.push($(this).attr("data-partyid"));
		})
		return partyids;
	}
	
	function removeParty(partyid){
		$(".partyList li").each(function(){
			if($(this).attr("data-partyid") == partyid){
				$(this).remove();
			}
		})
	}
	
	function getSelectPartyJson(){
		var partyList = [];
		$(".partyList li").each(function(){
			partyList.push({
				partyid:$(this).attr("data-partyid"),
				name:$(this).attr("title")
			});
		})
		return partyList;
	}
	
	$(".partyList li").live('click',function(){
		var partyid = $(this).attr("data-partyid");
		$(this).remove();
		var treeObj = $.fn.zTree.getZTreeObj("treeParty");
		var node = treeObj.getNodeByParam("partyid",partyid, null);
		if(node){
			var aObj = $('#' + node.tId + '_a');
			aObj.find(".jstree-custom-checked").removeClass("selected");
		}
	})

	$(":input[name='search']").click(function(){
		var index = $(":input[name='search']").index($(this));
		if($(":input[name='keyword']").val() != ''){
			var keyword = $(":input[name='keyword']").val().toLowerCase();
			
			var treeObj = $.fn.zTree.getZTreeObj("treeParty");
			
			if(keyword == '') return false;
			var nodes = treeObj.getNodesByFilter(function(node){
				return (node.name.toLowerCase().indexOf(keyword) != -1);
			});
			treeObj.cancelSelectedNode();
			if(nodes.length>0){
				if(nodes.length>20){
					parent.artDialog.message("搜索结果太多，您可以输入精确条件查询！",function(){});
				}
				$(nodes).each(function(index,node){
					treeObj.selectNode(node,true);
				})
			}else{
				Control.message("未搜索到结果！");
			}
		}
	})
});


</script>
