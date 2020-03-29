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
	
		input[type="text"]{width: 270px;}
		.schoolDisplay{ display:none;}
		.viewRuleBtn{ height:30px; line-height:30px;}
		.viewRuleBtn a{ text-decoration:none; color:#006600;}
		.viewRule{ display:none;}
    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered definewidth m10">
    <tr>
      <td width="10%" class="tableleft"><?=L("system.rolename")?></td>
      <td><input type="text" name="rolename" class="input-xlarge" data-required="{required:true}"  />
        <label class="tips">*</label></td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("base.status")?></td>
      <td><input name="status" type="radio" id="status" value="1" checked />
        <?=L("base.enable")?>
        <input type="radio" name="status" id="status" value="0" />
        <?=L("base.disable")?> </td>
    </tr>

    <tr>
      <td class="tableleft"><?=L("base.sort")?></td>
      <td><input name="sort" type="text" id="sort" class="input-xlarge" data-required="{required:true,number:true}">
        <label class="tips"> <?=L("msg.sort_list_by_number")?> </label></td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("system.power")?></td>
      <td>
		<div class="viewRuleBtn"> <a href="jsvascript:void(0)" class="switch"> <i class="icon-chevron-down"></i> <span><?=L("open.setPower")?></span> </a> </div>
		<div class="viewRule">
		<?php if($list) {?>
		<?php foreach($list as $rss) {?>
		<div class="panel" style="margin-bottom:10px;">
		  <div class="panel-header">
			<h3 style="margin-bottom:0px;"><input name="menuid[]" class="root" type="checkbox" value="<?=$rss["menuid"]?>"> <?=$rss["name"]?></h3>
		  </div>
		  <div class="panel-body">
			
			<div class="rolePannel">
				<?php if($rss["menu"]) {?>
				<?php foreach($rss["menu"] as $k=>$val) {?>
				<ul class="menuPannel">
					<dt><input name="menuid[]" type="checkbox" class="menu" value="<?=$val["menuid"]?>"> <?=$val["name"]?></dt>
					<?php if(isset($val["items"])) {?>
					<?php foreach($val["items"] as $res) {?>
					<li class="itemPannel">
						<div><input name="itemid[]" type="checkbox" class="item" value="<?=$res["itemid"]?>"> <?=$res["name"]?></div>
						<?php if(isset($res["action"]) && $res["action"]) {?>
						<p> 
						<?php foreach($res["action"] as $rs){?>
						<input name="actionid[<?=$res["itemid"]?>][]" type="checkbox" class="action" value="<?=$rs["actionid"]?>"> <?=(C('language')==1)?$rs["name"]:$rs["method"]?>
						<?php }?>
						</p>
						<?php }?>
					</li>
					<?php }?>
					<?php }?>
				</ul>
				<?php }?>
				<?php }?>
			</div>
		  </div>
		</div>
		<?php }?>
		<?php }?>
		</div>	  </td>
    </tr>

    <tr>
      <td class="tableleft"></td>
      <td><button name="Submit" class="btn btn-primary" type="button"><?=L("base.save")?></button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function () {
	Control.onSubmit();
	
	checkRole();
	
	var viewRule = false;
	$(".switch").click(function(){
		if(viewRule == false){
			$(this).find("span").text("<?=L("hidden.seetings")?>");
			$(this).find("i").attr("class","icon-chevron-up");
			$(".viewRule").show();
			viewRule = true;
		}else{
			$(this).find("span").text("<?=L("open.setPower")?>");
			$(this).find("i").attr("class","icon-chevron-down");
			$(".viewRule").hide();
			viewRule = false;
		}
	})

}); 
</script>