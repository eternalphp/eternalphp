<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.validator.js"></script>
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
<body>
<form class="form-inline definewidth m20" action="" method="POST">
  &nbsp;&nbsp;<button type="button" class="btn btn-success" id="add" name="add"><?=L("base.add")?></button> 
  &nbsp;&nbsp;<button type="button" class="btn btn-success" name="Submit"><?=L("base.save")?></button>
</form>

<form id="form1" action="<?=autolink(array('menu','save_items','menuid'=>get("id")))?>" method="post" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover definewidth m10">
    <thead>
      <tr>
        <th width="17%" align="left"><?=L("menu.name")?></th>
        <th width="21%" align="left"><?=L("base.module")?></th>
        <th width="22%" align="left"><?=L("base.method")?></th>
        <th width="30%" align="left"><?=L("base.sort")?></th>
        <th width="10%" align="center"><?=L("base.operate")?></th>
      </tr>
    </thead>
    <?php
  if($list){
    foreach($list as $key=>$val){
	  $item = array();
	  $item[] = array('url'=>autolink(array("menu","remove_item","id"=>$val["menuid"])),'name'=>'remove','text'=>L("base.remove"));
  ?>
    <tr class="listview">
      <td align="left"><input name="itemid[]" type="hidden" value="<?=$val["itemid"]?>"><input name="name[]" type="text" id="name" value="<?=$val["name"]?>"></td>
      <td align="left"><input name="action[]" type="text" id="action[]" value="<?=$val["action"]?>"></td>
      <td align="left"><input name="method[]" type="text" id="method" value="<?=$val["method"]?>"></td>
      <td align="left"><input name="sort[]" type="text" id="sort" value="<?=$val["sort"]?>"></td>
      <td align="center"><?=menulink($item)?></td>
    </tr>
    <?php
    }
  }
  ?>
    <tr class="listview">
      <td align="left"><input name="itemid[]" type="hidden" value=""><input name="name[]" type="text" id="name" value=""></td>
      <td align="left"><input name="action[]" type="text" id="action" value=""></td>
      <td align="left"><input name="method[]" type="text" id="method" value=""></td>
      <td align="left"><input name="sort[]" type="text" id="sort" value=""></td>
      <td align="center"><a href="javascript:void(0)" class="removeItem"><?=L("base.remove")?></a></td>
    </tr>
  </table>
  <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</form>

</body>
</html>
<script>
$(function () {

	$("#add").click(function(){
	   var dom = $(".listview:last").clone();
	   $(dom).insertAfter($(".listview:last"));
	})
	
	Control.onSubmit();
	
	Control.remove();
	
	$(".removeItem").live('click',function(){
	   if($(".removeItem").length>1){
	      $(this).parent().parent().remove();
	   }else{
	      alert("<?=L("msg.cannot_deleted_lastone")?>");
	   }
	})

})
</script>
