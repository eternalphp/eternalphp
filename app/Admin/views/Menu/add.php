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

		.hidden{ display:none;}
		
		.multipleSelect{ width:300px; border:#cccccc 1px solid; border-radius:4px;}
		.multipleSelect ul{ padding:0px; margin:0px;}
		.multipleSelect ul li{ list-style:none; height:40px; line-height:40px; text-indent:10px;}
    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
    <tr>
      <td width="15%" class="tableleft"><?=L("system.menus")?></td>
      <td>
	     <select name="parentid" class="input-xlarge">
          <option value="0"><?=L("system.topmenu")?></option>
          <?php if($list) {?>
		  <?php foreach($list as $val){ ?>
          <option value="<?=$val["menuid"]?>" parentid="<?=$val["parentid"]?>"><?=$val["name"]?></option>
		  <?php }?>
		  <?php }?>
         </select>	  </td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("system.menuname")?>[<?=L("base.chinese")?>]</td>
      <td><input type="text" name="name" class="input-xlarge" data-required="{required:true}"/>
        <label class="tips">*</label></td>
    </tr>
	
    <tr class="menu_item hidden">
      <td class="tableleft"><?=L("base.action")?></td>
      <td>
        <input name="class" type="text" id="class" class="input-xlarge">
      <label></label></td>
    </tr>
	
    <tr class="menu_item hidden">
      <td class="tableleft"><?=L("base.method")?></td>
      <td>
        <input name="method" type="text" id="method" class="input-xlarge">
      <label></label></td>
    </tr>
	
    <tr class="menu_item hidden">
      <td class="tableleft"><?=L("system.select_action")?></td>
      <td>
      	  <div class="multipleSelect">
		  	  <ul>
			     <?php if($action) {?>
				 <?php foreach($action as $val) {?>
			  	 <li> <input name="actionid[]" type="checkbox" value="<?=$val["actionid"]?>">  <?=(C("language")==1)?$val["name"]:$val["method"]?> </li>
				 <?php }?>
				 <?php }?>
			  </ul>
		  </div>	  </td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("base.ishidden")?></td>
      <td>
        <select name="hidden" class="input-xlarge">
          <option value="0"><?=L("system.no")?></option>
          <option value="1"><?=L("system.yes")?></option>
        </select>
      <label></label></td>
    </tr>

    <tr>
      <td class="tableleft"><?=L("base.sort")?></td>
      <td><input type="text" name="sort" value="" class="input-xlarge" />      </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary"> <?=L("base.save")?> </button></td>
    </tr>
  </table>
  
  <input name="item_menuid" type="hidden" value="">
  
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> 
</body>
</html>
<script>
$(function(){
    Control.onSubmit();
	
	$(":input[name='parentid']").change(function(){
		if($(this).find("option:selected").attr("parentid")>0){
			$(".menu_item").removeClass("hidden");
			$(":input[name='item_menuid']").val($(this).find("option:selected").val());
		}else{
			$(".menu_item").addClass("hidden");
			$(":input[name='item_menuid']").val('');
		}
	})
});
</script>
