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
<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        .user-content-view{
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }
        .seeMore{
            display: none;
        }
        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .face{display: inline-block;width: 30px;height: 30px;margin: 0 6px 0 2px;vertical-align: middle;border: 1px solid #cdcdcd;border-radius: 2px;}
    </style>
</head>
<body>
<form class="form-inline definewidth m20" action="" method="post">
  <button type="button" class="btn btn-success" id="addnew" name="add">添加</button>
</form>
<table width="100%" class="table table-bordered table-hover definewidth m10 table-striped">
  <thead>
    <tr>
      <th width="15%" align="center">群组名称</th>
      <th width="9%" align="center">人数</th>
      <th align="center">用户</th>
      <th width="13%" align="center">操作</th>
    </tr>
  </thead>
  <?php
   if($list){
    foreach($list as $k=>$val){
	  $item = array();
	  $item[] = array('url'=>autolink(array("tags","edit","tagid"=>$val["tagid"])),'name'=>'edit','text'=>'编辑');
	  $item[] = array('url'=>autolink(array("tags","remove","tagid"=>$val["tagid"])),'name'=>'remove','text'=>'删除');

  ?>
  <tr class="listview">
    <td align="center"><?=$val["tagname"]?></td>
    <td align="center"><?=$val["userCount"]?></td>
    <td align="left"><p class="user-content-view"><?=$val["user"]?></p> 
    <a href="javascript:;" class="seeMore" style="display: none;">查看更多</a></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="6" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
  <?php
  }
  ?>
</table>
</body>
</html>
<script>
$(function () {
    var line=$(".user-content-view").css('-webkit-line-clamp');
    var centent=$(".user-content-view");
    var seeMore=$(".seeMore");
    $.each(centent,function(i,d){
        var strCon=$(d).text().length;        // 字数
        if(strCon>=100){
            $(d).next('.seeMore').show();
        }else{
            $(d).next('.seeMore').hide();
        }
    })
	
	//新增
	$("#addnew").click(function(){
	    artDialog.open("<?=autolink(array('tags','add'))?>",{id:'add',title:"创建群组",width:"80%",height:600}).lock();
	})
	
	//编辑
	$(".edit").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'edit',title:"编辑群组",width:"80%",height:600}).lock();
	})
	
	//删除数据
	Control.remove();

    $(".seeMore").click(function(){
        var showSta=$(this).prev('p').css('display');
        if(showSta=='block'){
            $(this).text('查看更多');
            $(this).prev('p').css({display:'-webkit-box'});
        }else{
            $(this).text('收起');
            $(this).prev('p').css({display:'block'});
        }
    });
	
	var menuAction = <?=$menuAction?>;
	menuAction.push('seeMore');
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
})
 
</script>
