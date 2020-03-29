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

    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
    <tr>
      <td class="tableleft">群组名称</td>
      <td>
        <input name="tagname" type="text" id="tagname" value="<?=$row["tagname"]?>" size="60" data-required="{required:true}">
        <label class="red">*</label>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">选择用户</td>
      <td>
		<div class="searchPannel">
		  <input class="span3" name="keyword" placeholder="请输入关键词" type="text">
		  <button type="button" name="search" class="btn"> 搜索 </button>
		</div>
	   <div style=" height:430px; overflow:hidden;overflow-y:auto;">
          <ul id="treeParty" class="ztree">
          </ul>
        </div>
        <input name="userids" type="hidden" id="userids"></td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary" style="width:120px;"> 保存 </button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){

	Control.onSubmit(function(){
		validateInput('userids','userid');
	})
	
    
	var zNodes =<?=$partyUser?>;
	$.fn.zTree.init($("#treeParty"), setting, zNodes);
	var treeObj = $.fn.zTree.getZTreeObj("treeParty");
	var userid="<?=$row["userids"]?>";
	var user = userid.split("|");
	$(user).each(function(index,userid){
		var node = treeObj.getNodesByParam("userid",userid, null);
		if(node.length>0) treeObj.checkNode(node[0], true, true);
	})
	
	$(":input[name='search']").click(function(){
		if($(":input[name='keyword']").val() != ''){
			var keyword = $(":input[name='keyword']").val().toLowerCase();
			
			var treeObj = $.fn.zTree.getZTreeObj("treeParty");
			if(keyword == '') return false;
			var nodes = treeObj.getNodesByFilter(function(node){
				return (node.name.toLowerCase().indexOf(keyword)!=-1);
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
