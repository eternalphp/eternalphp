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

 <form class="form-inline definewidth m20" action="<?=SEARCH_ACTION?>" method="post">
  &nbsp;&nbsp;<?=L("name")?>：
  <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=isset($_REQUEST["keyword"])?$_REQUEST["keyword"]:''?>">
  &nbsp;&nbsp;
  <button type="submit" class="btn btn-primary"><?=L("search")?></button>&nbsp;&nbsp;<button type="button" class="btn btn-success" id="addnew" name="add"><?=L("add")?></button>
 </form>

<table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th align="left"><?=L("name")?></th>
      <th width="11%"><?=L("sort")?></th>
      <th width="14%"><?=L("manage")?></th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){
		
	  $item=array();
	  $item[]=array('url'=>autolink(array("menu","edit","id"=>$val["menu_id"])),'name'=>'edit','text'=>L("edit"),'type'=>'url');
	  $item[]=array('url'=>autolink(array("menu","remove","id"=>$val["menu_id"])),'name'=>'remove','text'=>L("delete"));
	  if($val["super_id"]>0){
	     $item[]=array('url'=>autolink(array("menu","items","id"=>$val["menu_id"])),'name'=>'view','text'=>L("view"));
	  }

  ?>
  <tr class="listview">
    <td><?=$val["menu_name"]?></td>
    <td><?=$val["sort"]?></td>
    <td><?=menulink($item)?></td>
  </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="4" align="center"><div class="nodata"><?=L("nodata")?></div></td>
  </tr>
  <?php
  }
  ?> 
</table>
</body>
</html>
<script>
$(function () {
	$('#addnew').click(function(){
		window.location.href="<?=autolink(array("menu","add"))?>";
	});
	
	
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
	
	$(".view").click(function(){
		$(this).css("color","red");
	    artDialog.open($(this).attr("url"),{title:'<?=L("dialog_menu_title")?>',width:1000,height:500});
	})
	
})
</script>
