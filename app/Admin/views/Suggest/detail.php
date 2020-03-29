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

<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>

<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        .user-bold{ font-weight: bold;}
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
  <form id="form1" class="definewidth m20" action="<?=autolink(array(get('class'),'reply','newsid'=>$row["newsid"]))?>" method="post" target="hidden_frame">
  <table width="100%" class="table table-bordered definewidth m10">
    <tr>
      <td width="9%" class="tableleft">姓名</td>
      <td width="91%"><?=$row["name"]?></td>
    </tr>
	
    <tr>
      <td width="9%" class="tableleft">手机号</td>
      <td width="91%"><?=$row["mobile"]?></td>
    </tr>
	
    <tr>
      <td width="9%" class="tableleft">反馈问题</td>
      <td width="91%"><?=$row["content"]?></td>
    </tr>
	
	<?php if($row["files"]) {?>
    <tr>
      <td width="9%" class="tableleft">图片</td>
      <td width="91%">
	  	  <?php foreach($row["files"] as $filename) {?>
	  	  <img src="<?=$filename?>" width="300" class="thumbnail" />
		  <?php }?>
	  </td>
    </tr>
	<?php }?>
	
    <tr>
      <td width="9%" class="tableleft">反馈时间</td>
      <td width="91%"><?=$row["createtime"]?></td>
    </tr>
	
    <tr>
      <td width="9%" class="tableleft">状态</td>
      <td width="91%"><?=($row["status"] == 1)?"已处理":"未处理"?></td>
    </tr>
	
	<?php if($row["status"] == 1) {?>
    <tr>
      <td width="9%" class="tableleft">回复内容</td>
      <td width="91%"><?=$row["reply"]?></td>
    </tr>
	<?php }?>
	
	<?php if($row["status"] == 0) {?>
    <tr>
      <td valign="middle" class="tableleft">回复内容</td>
      <td>
			<textarea name="reply" cols="60" rows="5"></textarea>
	  </td>
    </tr>
    <tr>
      <td valign="top" class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary"> 提交 </button></td>
    </tr>
	<?php }?>
</table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>

</body>
</html>
<script>

$(function(){
	Control.onSubmit();
});
</script>