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
  关键词：
  <input type="text" name="keyword" id="keyword"class="abc input-default" value="<?=request("keyword")?>">
  &nbsp;&nbsp;<button type="submit" class="btn btn-primary">搜索</button>
 </form>


 <table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="6%" align="center">序号</th>
      <th width="12%" align="center">姓名</th>
	  <th width="15%" align="center">所属支部</th>
      <th width="10%" align="center">获得积分</th>
      <th width="11%" align="center">获得积分原因</th>
      <th width="11%" align="center">获得积分时间</th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){
  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><?=$val["party_name"]?></td>
    <td align="center"><?=$val["score"]?></td>
    <td align="center"><?=$val["reason"]?></td>
    <td align="center"><?=$val["createtime"]?></td>
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

<table width="100%" class="definewidth m10">
  <tr>
    <td align="right">总计数<?=$total?>条 <?=$pagelink?></td>
  </tr>
</table>

 
</body>
</html>
<script>
$(function () {
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);

})
 
</script>