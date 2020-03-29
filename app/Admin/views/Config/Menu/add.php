<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/style.css" />
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.validator.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/plugins/iframeTools.source.js"></script>
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
      <td width="10%" class="tableleft"><?=L("super_menu")?></td>
      <td>
	     <select name="super_id">
          <option value="0"><?=L("super_menu_name")?></option>
          <?php
		    foreach($super_menu as $val){
		  ?>
          <option value="<?=$val["menu_id"]?>"><?=$val["menu_name"]?></option>
          <?php
		   }
		  ?>
         </select>      
	  </td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("menu_name")?></td>
      <td><input type="text" name="menu_name" data-required="{required:true}"/>
        <label><?=L("menu_empty_msg")?></label></td>
    </tr>
	
    <tr>
      <td class="tableleft"><?=L("sort")?></td>
      <td><input type="text" name="sort" value=""/>      </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary"> <?=L("save")?> </button> <button type="button" class="btn btn-success" name="backid" id="backid"><?=L("back")?></button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe> 
</body>
</html>
<script>
$(function(){

	$('#backid').click(function(){
	   window.location.href="<?=autolink(array("menu"))?>";
	});
    
    $(":input[name='Submit']").click(function(){
	   if(validate.check($("#form1"),{})){
		  $("#form1").submit();
	   }
	})
});

//回调方法
function callback(message,success) 
{ 
     parent.artDialog.message(message,function(){
		 if(success==true){
		    window.location.href="<?=autolink(array("menu","index"))?>";
		 }
	 });
} 
</script>
