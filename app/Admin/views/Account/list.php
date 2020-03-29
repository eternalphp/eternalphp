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
  创建时间从
  <input type="text" name="starttime" class="date" value="<?=request("starttime")?>" />
  到
  <input type="text" name="endtime" class="date" value="<?=request("endtime")?>" />
  &nbsp;&nbsp;姓名/帐号：
  <input type="text" name="keyword" id="keyword" value="<?=request("keyword")?>">
  &nbsp;&nbsp;选择部门：<?=HtmlControl()->Select("partyid",$partyTree,request("partyid"))->field("name","id")->setDefault("- 请选择 -",0)->create();?>
  &nbsp;&nbsp;状态：
  <select name="status">
    <option value="0">所有状态</option>
    <option value="1" <?php if(request("status")==1) echo "selected";?>>正常</option>
    <option value="2" <?php if(request("status")==2) echo "selected";?>>禁用</option>
  </select>
  &nbsp;&nbsp;
  <button type="submit" class="btn btn-primary">搜索</button>
    
  <button type="button" class="btn btn-success" id="addnew" name="add">添加</button>
</form>
<table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="6%" align="center">序号</th>
      <th width="12%" align="center">帐号</th>
      <th width="16%" align="center">帐号类别</th>
      <th width="15%" align="center">所属部门</th>
      <th width="9%" align="center">姓名</th>
      <th width="9%" align="center">状态</th>
      <th width="10%" align="center">操作</th>
    </tr>
  </thead>
  <?php
   if($list){
    foreach($list as $k=>$val){
		
	  $item=array();
	  $item[]=array('url'=>autolink(array("account","edit","uid"=>$val["uid"])),'name'=>'edit','text'=>'编辑');
	  $item[]=array('url'=>autolink(array("account","remove","uid"=>$val["uid"])),'name'=>'remove','text'=>'删除');

  ?>
  <tr class="listview">
    <td align="center"><?=$k+1?></td>
    <td align="center"><?=$val["username"]?></td>
    <td align="center"><?=$val["rolename"]?></td>
    <td align="center"><?=$val["party_name"]?></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><?=($val["status"]==1)?'正常':'禁用'?></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="9" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
  <?php
  }
  ?>
</table>
<table width="100%" class="definewidth m10">
  <tr>
    <td align="right">总计数
      <?=$total?>
      条
      <?=$pagelink?></td>
  </tr>
</table>
</body>
</html>
<script>
$(function () {
	
	//新增
	$("#addnew").click(function(){
	    artDialog.open("<?=autolink(array('account','add'))?>",{id:'add',title:"添加帐号",width:"80%",height:600}).lock();
	})
	
	//编辑
	$(".edit").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'edit',title:"编辑帐号",width:"80%",height:600}).lock();
	})
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
	
})
 
</script>
