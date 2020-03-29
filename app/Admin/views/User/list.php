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

        .face{display: inline-block;width: 30px;height: 30px;margin: 0 6px 0 2px;vertical-align: middle;border: 1px solid #cdcdcd;border-radius: 2px;}
    </style>
</head>
<body>

 <form class="form-inline definewidth m20" action="" method="post">
 姓名/手机号：<input type="text" name="keyword" id="keyword" class="abc input-default" value="<?=request("keyword")?>">
  &nbsp;&nbsp;选择部门：<?=HtmlControl()->Select("partyid",$partyTree,request("partyid"))->field("name","id")->setDefault("- 所有支部 -",0)->create();?>
  &nbsp;&nbsp;选择职位：<?=HtmlControl()->Select("positionid",$positionList,request("positionid"))->field("name","positionid")->setDefault("- 所有职位 -",0)->create();?>
  &nbsp;&nbsp;状态：<select name="status"><option value="0">所有状态</option><option value="1" <?php if(request("status") == 1) echo "selected";?>>已关注</option><option value="2" <?php if(request("status") == 2) echo "selected";?>>已禁用</option><option value="4" <?php if(request("status") == 4) echo "selected";?>>未关注</option></select>
  &nbsp;&nbsp;<button type="submit" class="btn btn-primary">搜索</button>
  &nbsp;&nbsp;<button type="button" class="btn btn-success" id="addnew" name="add">添加</button>
  &nbsp;&nbsp;<button type="button" class="btn btn-success" id="import"> 导入成员 </button>
  &nbsp;&nbsp;<button type="button" class="btn btn-success" id="export"> 导出成员 </button>
 </form>


 <table width="100%" class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
	  <th width="3%" align="center"><input name="checkAll" type="checkbox" value=""></th>
      <th width="4%" align="center">头像</th>
      <th width="8%" align="center">姓名</th>
      <th width="9%" align="center">部门</th>
	  <th width="7%" align="center">职位</th>
      <th width="8%" align="center">用户身份</th>
      <th width="5%" align="center">性别</th>
	  <th width="8%" align="center">手机号</th>
	  <th width="7%" align="center">出生年月</th>
	  <th width="7%" align="center">文化程度</th>
	  <th width="7%" align="center">入党时间</th>
	  <th width="9%" align="center">来本单位时间</th>
	  <th width="8%" align="center">状态</th>
      <th width="10%" align="center">操作</th>
    </tr>
  </thead>

  <?php
   if($list){
    foreach($list as $k=>$val){
		
	  $item = array();
	  $item[] = array('url'=>autolink(array("user","edit","userid"=>$val["userid"])),'name'=>'edit','text'=>'编辑');
	  $item[] = array('url'=>autolink(array("user","detail","userid"=>$val["userid"])),'name'=>'view','text'=>'查看');
	  $item[] = array('url'=>autolink(array("user","remove","userid"=>$val["userid"])),'name'=>'remove','text'=>'删除');
  ?>
  <tr class="listview">
  	<td align="center"><input name="userid[]" type="checkbox" value="<?=$val["userid"]?>"></td>
    <td align="center"><img src="<?=($val["avatar_url"]!='')?$val["avatar_url"]:'__PUBLIC__/images/avatar_default.png'?>" class="face" /></td>
    <td align="center"><?=$val["name"]?></td>
    <td align="center"><?=$val["party_name"]?></td>
	<td align="center"><?=$val["position"]?></td>
	<td align="center"><?=$val["identity"]?></td>
	<td align="center"><?=($val["gender"] == 1)?"男":"女"?></td>
    <td align="center"><?=$val["mobile"]?></td>
	<td align="center"><?=$val["bothday"]?></td>
	<td align="center"><?=$val["education"]?></td>
	<td align="center"><?=($val["isPartyMember"] == 1)?$val["joinPartyDate"]:""?></td>
	<td align="center"><?=$val["entrydate"]?></td>
	<td align="center"><?=($val["status"]==4)?'<font color="red">未关注</font>':(($val["status"]==2)?'<font color="#FFFF00">已禁用</font>':'已关注')?></td>
    <td align="center"><?=menulink($item)?></td>
  </tr>
  <?php
	}
  }else{
  ?>
  <tr>
    <td height="30" colspan="15" align="center"><div class="nodata">没有数据……</div></td>
  </tr>
  <?php
  }
  ?> 
</table>

<table width="100%" class="definewidth m10">
  <tr>
    <td width="44%" align="left"><button type="button" class="btn btn-primary" style="width:160px;" id="updatePartyFeeDate"> 交纳党费 </button> &nbsp;&nbsp; <button type="button" class="btn btn-primary" style="width:160px;" id="modifyParty"> 修改部门 </button>&nbsp;&nbsp; <button type="button" class="btn btn-primary" style="width:160px;" id="handleSort"> 人员排序 </button> </td>
    <td width="56%" align="right">总计数<?=$total?>条 <?=$pagelink?></td>
  </tr>
</table>

 
</body>
</html>
<script>
$(function () {

	Control.checkAll({items:'userid[]'});
    
	
	//新增
	$("#addnew").click(function(){
	    artDialog.open("<?=autolink(array('user','add'))?>",{id:'add',title:"新增成员",width:"80%",height:"90%"}).lock();
	})
	
	//编辑
	$(".edit").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'edit',title:"编辑成员",width:"80%",height:"90%"}).lock();
	})
	
	//排序
	$("#handleSort").click(function(){
		if($(":input[name='userid[]']:checked").length == 0){
			Control.message("请至少选择一个成员！",function(){});
		}else{
			var userids = [];
			$(":input[name='userid[]']:checked").each(function(){
				userids.push($(this).val());
			})
			var height = '50%';
			if(userids.length > 10){
				height = '90%';
			}
      	    artDialog.open("<?=autolink(array(get('class'),'handleSort'))?>" + "?userids="+userids.join(","),{id:'handleSort',title:"设置排序",width:"60%",height:height}).lock();
		}
	})
	
	//编辑
	$(".view").click(function(){
	    $(this).css("color","red");
        artDialog.open($(this).attr("url"),{id:'view',title:"查看成员",width:"80%",height:"90%"}).lock();
	})
	
	$("#updatePartyFeeDate").click(function(){
		if($(":input[name='userid[]']:checked").length == 0){
			Control.message("请至少选择一个成员！",function(){});
		}else{
			var userids = [];
			$(":input[name='userid[]']:checked").each(function(){
				userids.push($(this).val());
			})
			artDialog.open("<?=autolink(array(get('class'),'memberFee'))?>"+"?userids="+userids.join(","),{id:'memberFee',title:"交纳党费",width:"700px",height:"300px"}).lock();
		}
	})
	
	$("#modifyParty").click(function(){
		if($(":input[name='userid[]']:checked").length == 0){
			Control.message("请至少选择一个成员！",function(){});
		}else{
			var userids = [];
			$(":input[name='userid[]']:checked").each(function(){
				userids.push($(this).val());
			})
			artDialog.open("<?=autolink(array(get('class'),'modifyParty'))?>"+"?userids="+userids.join(","),{id:'modifyParty',title:"修改部门",width:"700px",height:"300px"}).lock();
		}
	})
	
	$("#import").click(function(){
		artDialog.open("<?=autolink(array(get('class'),'importFile'))?>",{id:'import',title:"<?=L("base.importData")?>",width:"80%",height:"90%",opacity:0.4}).lock();
	})
	
	$("#export").click(function(){
		location.href = "<?=autolink(array(get('class'),'exportFile'))?>";
	})
	
	Control.remove();
	
	var menuAction = <?=$menuAction?>;
	menuAction.push('handlesort');
    auth.check($(".listview a[class]"),menuAction);
    auth.check($("button[name]"),menuAction);
})
 
</script>