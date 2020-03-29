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
    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered m10">
	
    <tr>
      <td width="15%" class="tableleft"> 选择下发人员 </td>
      <td>
		<div class="searchPannel">
		  <input class="span3" name="keyword" placeholder="请输入关键词" type="text" style="margin-bottom:0px;">
		  <button type="button" name="search" class="btn"> 搜索 </button>
		</div>
		<div style=" height:430px; overflow:hidden;overflow-y:auto;">
          <ul id="treeParty" class="ztree">
          </ul>
        </div>
		<input name="userids" id="userids" type="hidden" class="input-large" value="">
	  </td>
    </tr>
	
    <tr>
      <td width="15%" class="tableleft"> 选择群组 </td>
      <td>
		<div class="searchPannel">
		  <input class="span3" name="keyword" placeholder="请输入关键词" type="text" style="margin-bottom:0px;">
		  <button type="button" name="search" class="btn"> 搜索 </button>
		</div>
		<div style=" height:430px; overflow:hidden;overflow-y:auto;">
          <ul id="treeGroup" class="ztree">
          </ul>
        </div>
	  </td>
    </tr>
	
	
    <tr>
      <td class="tableleft"></td>
      <td>
		<button type="button" name="Submit" class="btn btn-primary"> 发布 </button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){
	Control.onSubmit(function(){
	    var users = [];
		validateInput('userids','userid',function(userids){
			if(userids.length>0){
				users = userids;
				$(":input[name='userids']").val(userids.join("|"));
			}
		});
		
		if(users.length>0){
			return true;
		}else{
			Control.message("请选择发送对象！");
			return false;
		}
	})
	
	setting.check.enable = true;
	var partyUser=<?=$partyUser?>;
	$.fn.zTree.init($("#treeParty"), setting,partyUser);
	
	var groupUser=<?=$groupUser?>;
	$.fn.zTree.init($("#treeGroup"), setting,groupUser);
	
	$(":input[name='search']").click(function(){
		var index = $(":input[name='search']").index($(this));
		if($(":input[name='keyword']").eq(index).val() != ''){
			var keyword = $(":input[name='keyword']").eq(index).val().toLowerCase();
			
			if(index == 0){
				var treeObj = $.fn.zTree.getZTreeObj("treeParty");
			}else{
				var treeObj = $.fn.zTree.getZTreeObj("treeGroup");
			}
			
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

function callback(res){ 
	top.artDialog.message(res.errmsg,function(){
		if(res.errcode == 0){
			var win = art.dialog.open.origin;//来源页面
			
			//win.reloadUrl();
			
			win.location.reload(); 
		}else{
			$(":input[name='Submit']").attr("disabled",false);
		}
	});
}
</script>
