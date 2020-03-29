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
<script type="text/javascript" src="__PUBLIC__/js/Request.js"></script>

<!--日期插件-->
<link href="__PUBLIC__/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="__PUBLIC__/plugins/datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

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

 <form class="form-inline definewidth m20" action="" method="post">
  创建时间从 
    <input type="text" name="starttime" class="date" value="<?=request("starttime")?>" /> 到 <input type="text" name="endtime" class="date" value="<?=request("endtime")?>" /> 
  &nbsp;&nbsp;标题：
  <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=request("keyword")?>">
  &nbsp;&nbsp;<button type="submit" class="btn btn-primary">搜索</button>
 </form>


 <table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="8%" align="center">序号</th>
      <th width="10%" align="center">姓名</th>
	  <th width="12%" align="center">手机号</th>
	  <th width="31%" align="left">反馈问题</th>
	  <th width="15%" align="center">反馈时间</th>
      <th width="10%" align="center">状态</th>
      <th width="14%" align="center">操作</th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){
      $item = array();
	  $item[] = array('url'=>autolink(array(get('class'),"detail","newsid"=>$val["newsid"])),'name'=>'view','text'=>'详情');
	  $item[] = array('url'=>autolink(array(get('class'),"remove","newsid"=>$val["newsid"])),'name'=>'remove','text'=>'删除');
  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["name"]?></td>
	<td align="center"><?=$val["mobile"]?></td>
	<td><?=$val["content"]?></td>
	<td align="center"><?=$val["createtime"]?></td>
    <td align="center"><?=($val["status"] == 1)?"已处理":"待处理"?></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="8" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
  <?php
  }
  ?> 
</table>

<table width="100%" class="definewidth m10">
  <tr>
    <td align="right">总计数<?=$total?>条 <?=$pagelink?></td>
  </tr>
</table>

 
</body>
</html>
<script>
$(function () {
	
	//查看
	$(".view").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'view',title:"查看详情",width:"80%",height:"90%"}).lock();
	})
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);

})
 
</script>