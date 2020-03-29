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


    </style>
</head>
<body>
<form id="form1" action="<?=autolink(array(get('class'),'save'))?>" method="post" class="definewidth m20" target="hidden_frame">
 <table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="8%" align="center">序号</th>
      <th width="32%" align="center">积分项目</th>
	  <th width="35%" align="center">操作方式</th>
      <th width="25%" align="center">获得积分</th>
    </tr>
  </thead>

  <?php if($list){?>
  <?php foreach($list as $k=>$val){ ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["title"]?></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><input name="items[<?=$val["itemid"]?>]" type="number" value="<?=$val["score"]?>"></td>
   </tr>
  <?php }?>
  <tr>
    <td height="30" colspan="4" align="center"><button type="button" name="Submit" class="btn btn-primary" style="width:120px;"> 确认 </button></td>
  </tr>
  <?php }else {?>
  <tr>
    <td height="30" colspan="4" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
  <?php } ?> 
</table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
 
</body>
</html>
<script>
$(function () {

	Control.onSubmit();

})
 
</script>