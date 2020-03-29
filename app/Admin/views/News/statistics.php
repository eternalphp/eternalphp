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
 </form>


 <table width="100%" class="table table-bordered definewidth m10">
  <thead>
    <tr>
      <th width="14%" align="center">排名</th>
      <th width="20%" align="center">支部</th>
	  <th width="25%" align="center">人员</th>
      <th width="21%" align="center">是否阅读</th>
      <th width="20%" align="center">阅读率</th>
      </tr>
  </thead>

  <?php
   $total = 0;
   $reads = 0;
   $percent = 0;
   $k=1;
   if($list){
   foreach($list as $partyid=>$vals){
		$total += $vals["total"];
		$reads += $vals["reads"];
  ?>
  <tr class="listview">
      <td rowspan="<?=count($vals["user"])?>" align="center"><?=$k?></td>
      <td rowspan="<?=count($vals["user"])?>" align="center" valign="middle"><?=$vals["name"]?></td>
	  <?php if($vals["user"]) {?>
	  <?php foreach($vals["user"] as $kk=>$val) {?>
	  <?php if($kk == 0) {?>
      <td align="center"><?=$val["name"]?></td>
      <td align="center"><?=($val["read"] == 1)?"是":"否"?></td>
      <td rowspan="<?=count($vals["user"])?>" align="center" valign="middle"><?=$vals["percent"]?></td>
	  <?php }?>
	  <?php }?>
	  <?php }?>
  </tr>
	  <?php if($vals["user"] && count($vals["user"])>1) {?>
	  <?php foreach($vals["user"] as $kk=>$val) {?>
	  <?php if($kk > 0) {?>
	  <tr class="listview">
		  <td align="center"><?=$val["name"]?></td>
		  <td align="center"><?=($val["read"] == 1)?"是":"否"?></td>
	  </tr>
	  <?php }?>
	  <?php }?>
	  <?php }?>
  <?php $percent = ($total>0)?(round(($reads/$total),2)*100)."%":"0%";?>
  <?php $k++;?>	  
  <?php }?>
  <tr>
      <td height="30" align="center">总计</td>
      <td height="30" align="center"><?=count($list)?>个支部</td>
      <td height="30" align="center"><?=$total?>人</td>
      <td height="30" align="center"><?=$reads?>人已阅读</td>
      <td height="30" align="center"><?=$percent?></td>
  </tr>
  <?php }else{?>
  <tr>
    <td height="30" colspan="5" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
<?php }?>
</table>

</body>
</html>