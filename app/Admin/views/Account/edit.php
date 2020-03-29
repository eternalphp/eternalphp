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
<script type="text/javascript" src="__PUBLIC__/js/HtmlControl.js"></script>

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
		
		.table input[type='text']{ width:270px;}

    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered m10">
    <tr>
      <td width="10%" class="tableleft">选择用户</td>
      <td><input name="userid" fieldname="userid" treeId="treeDemo" type="hidden" bindHtmlControl="selectDropdown" class="input-xlarge" style="width:270px;" value="<?=$row["userid"]?>">
      </td>
    </tr>
	
    <tr>
      <td class="tableleft">选择角色</td>
      <td><?=HtmlControl()->Select("roleid",$roleList,$row["roleid"])->field("rolename","roleid")->setDefault(" - 请选择 - ",0)->attr("style","width:270px;")->create();?>
        <label></label>
      </td>
    </tr>
	
    <tr>
      <td class="tableleft">登录帐号</td>
      <td>
	  <input type="text" name="username" data-required="{required:true}" value="<?=$row["username"]?>" class="input-xlarge" style="width:270px;" /> <label></label>      </td>
    </tr>
	
	
    <tr>
      <td class="tableleft">登录密码</td>
      <td>
	  <input type="text" id="password" name="password" class="input-xlarge" /> 
	  <label></label>      </td>
    </tr>
	
    <tr>
      <td class="tableleft">确认密码</td>
      <td>
	  <input type="text" name="checkpass" class="input-xlarge"  /> 
	  <label></label>      </td>
    </tr>

	
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary">  保存  </button>        </td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){
    Control.onSubmit(function(){
		if($(":input[name='roleid']").val() == 0){
			Control.message("请选择角色！",function(){});
			return false;
		}
	});
    
	HtmlControl.selectDropdown();
	setting.check.enable = false;
	setting.user_callback = function(treeNode){
		$(":input[name='username']").val(treeNode.mobile);
	}
	var zNodes =<?=$partyUser?>;
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	HtmlControl.selectDropdownItem();

});
</script>
