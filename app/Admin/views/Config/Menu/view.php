<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/style.css" />
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/plugins/iframeTools.source.js"></script>
<link href="<?=TEMP_PATH?>/upload/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?=TEMP_PATH?>/upload/uploadify/jquery.uploadify-3.1.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/table.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/common.js"></script>


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
<form class="form-inline definewidth m20" action="<?=SEARCH_ACTION?>" method="POST">
  <button type="button" class="btn btn-success" id="add" name="add"><?=L("add")?></button> &nbsp;&nbsp;<button type="button" class="btn btn-success" id="save" name="save"><?=L("save")?></button>
</form>

<form id="formSave" action="<?=autolink(array('menu','save_items','menu_id'=>get("id")))?>" method="post" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover definewidth m10">
    <thead>
      <tr>
        <th align="left"><?=L("name")?></th>
        <th width="18%" align="left"><?=L("module")?></th>
        <th width="18%" align="left"><?=L("method")?></th>
        <th width="18%" align="left"><?=L("sort")?></th>
        <th width="18%" align="left">是否隐藏</th>
        <th width="10%" align="left"><?=L("manage")?></th>
      </tr>
    </thead>
    <?php
  if(count($list)>0){
    foreach($list as $key=>$val){
  ?>
    <tr class="listview">
      <td align="left"><input name="item_id[]" type="hidden" value="<?=$val["item_id"]?>"><input name="item_name[]" type="text" id="item_name" value="<?=$val["item_name"]?>"></td>
      <td align="left"><input name="action[]" type="text" id="action[]" value="<?=$val["action"]?>"></td>
      <td align="left"><input name="method[]" type="text" id="method" value="<?=$val["method"]?>"></td>
      <td align="left"><input name="sort[]" type="text" id="sort" value="<?=$val["sort"]?>"></td>
      <td align="left"><input name="hidden[<?=$key?>]" type="checkbox" id="hidden" value="1" <?php if($val["hidden"]==1) echo "checked";?>></td>
      <td><a href="javascript:void(0)" class="remove" url="<?=autolink(array("menu","remove_item","id"=>$val["item_id"]))?>"><?=L("delete")?></a></td>
    </tr>
    <?php
    }
  }
  ?>
    <tr class="listview">
      <td align="left"><input name="item_id[]" type="hidden" value=""><input name="item_name[]" type="text" id="item_name" value=""></td>
      <td align="left"><input name="action[]" type="text" id="action" value=""></td>
      <td align="left"><input name="method[]" type="text" id="method" value=""></td>
      <td align="left"><input name="sort[]" type="text" id="sort" value=""></td>
      <td align="left"><input name="hidden[]" type="checkbox" id="hidden" value="1"></td>
      <td><a href="javascript:void(0)" class="removeItem"><?=L("remove")?></a></td>
    </tr>
  </table>
  <iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</form>

</body>
</html>
<script>
$(function () {
    
	tableList.sort($(".table"));
	
	//删除数据
	$(".remove").click(function(){
	  var $this=$(this);
	   parent.artDialog.confirm("<?=L("remove_msg")?>",function(){
	       $.ajax({
		     url:$this.attr("url"),
			 type:"GET",
			 dataType:'json',
			 success:function(data){
			    if(data.status=='ok'){
				   $this.parent().parent().remove();
				   parent.art.dialog.tips(data.msg);
				}else{
				   parent.art.dialog.tips(data.msg);
				}
			  },
			  error:function(data){
			     document.write(data.responseText);
			  }
		   })
		   
		},function(){
		   parent.art.dialog.tips("<?=L("cancel_msg")?>");
		})
	})

	
	$("#add").click(function(){
	   var dom=$(".listview:last").clone();
	   $(dom).insertAfter($(".listview:last"));
	})
	
	$("#save").click(function(){
	   $("#formSave").submit();
	})
	
	
	$(".removeItem").live('click',function(){
	   if($(".removeItem").length>1){
	      $(this).parent().parent().remove();
	   }else{
	      alert("<?=L("is_last_msg")?>");
	   }
	})

})

//回调方法
function callback(message,success) 
{ 
     parent.artDialog.message(message,function(){
		 if(success==true){
		     location.reload();
		 }
	 });
}
</script>
