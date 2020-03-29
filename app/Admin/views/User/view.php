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

  <table width="100%" class="table table-bordered table-hover definewidth m10">
	
    <tr>
      <td width="10%" class="tableleft">姓名</td>
      <td><?=$row["name"]?></td>
    </tr>
    <tr>
      <td class="tableleft">性别</td>
      <td><?=($row["gender"] == 1)?"男":"女"?></td>
    </tr>
	
    <tr>
      <td class="tableleft">部门</td>
      <td><?=$row["party_name"]?></td>
    </tr>
	
    <tr>
      <td class="tableleft">党内职位</td>
      <td><?=$row["position"]?> <?=($row["position_other"] != '')? "、" .$row["position_other"]:""?></td>
    </tr>
	
    <tr>
      <td valign="top" class="tableleft">出生年月</td>
      <td valign="top"><?=$row["bothday"]?></td>
    </tr>
    <tr>
      <td class="tableleft">文化程度</td>
      <td><?=$row["education"]?></td>
    </tr>
	
    <tr>
      <td class="tableleft">政治面貌</td>
      <td><?=$row["political"]?></td>
    </tr>

    <tr>
      <td class="tableleft">来本单位时间</td>
      <td><?=$row["entrydate"]?></td>
    </tr>
	
	<?php if($row["isPartyMember"] == 1) {?>
    <tr>
      <td class="tableleft">入党时间</td>
      <td><?=$row["joinPartyDate"]?></td>
    </tr>
    <tr>
      <td class="tableleft">党费交纳至</td>
      <td><?=$row["year"]?>年 - <?=$row["month"]?>月</td>
    </tr>
		<?php if($row["register"]) {?>
		<?php foreach($row["register"] as $k=>$val) {?>
		<tr>
		  <td class="tableleft">双报到时间</td>
		  <td><?=$val["registerDate"]?></td>
		</tr>
		<tr>
		  <td class="tableleft">所在街镇社区</td>
		  <td><?=$val["address"]?></td>
		</tr>
		<?php }?>
		<?php }?>
	<?php }?>
</table>

</body>
</html>