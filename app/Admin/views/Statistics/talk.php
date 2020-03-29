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
  关键词：
  <input type="text" name="keyword" id="keyword"class="abc input-default" placeholder="请输入支部名称" value="<?=request("keyword")?>">
  &nbsp;&nbsp;请选择年份 <select name="year"> <?php for($year = date("Y",strtotime("-5 year"));$year<=date("Y");$year++) {?> <option value="<?=$year?>" <?php if(request("year",date("Y")) == $year) echo "selected";?>> <?=$year?>年 </option> <?php }?> </select>
  &nbsp;&nbsp;<button type="submit" class="btn btn-primary">搜索</button>
 </form>


 <table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="3%" align="center">序号</th>
      <th width="53%" align="center">所属支部</th>
	  <th width="44%" align="center">谈心谈话次数</th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){

  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><?=$val["total"]?></td>
   </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="3" align="center"><div class="nodata">没有数据……</div></td>
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
