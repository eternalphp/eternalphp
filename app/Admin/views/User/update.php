<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-store" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/Request.js"></script>

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
		
		.viewList{ height:400px; width:100%; overflow:auto;}
		.viewList ul{ padding:0px; margin:0px; width:800%;}
		.viewList ul li{ height:30px; line-height:30px; list-style:none; font-size:14px;}
		.viewList ul li span{width:300px;padding:0px 10px;}
		.success{ color:#468847;}
		.warning{ color:#f89406;}
    </style>
</head>
<body>
 <form class="form-inline definewidth m20" action="" method="post">
 姓名/手机号：<input type="text" name="keyword" id="keyword" class="abc input-default" value="<?=request("keyword")?>">
  &nbsp;&nbsp;选择部门：<?=HtmlControl()->Select("partyid",$partyTree,request("partyid"))->field("name","id")->setDefault("- 所有支部 -",0)->create();?>
  &nbsp;&nbsp;状态：<select name="status"><option value="0">所有状态</option><option value="1" <?php if(request("status") == 1) echo "selected";?>>已关注</option><option value="2" <?php if(request("status") == 2) echo "selected";?>>已禁用</option><option value="4" <?php if(request("status") == 4) echo "selected";?>>未关注</option></select>
  &nbsp;&nbsp;<button type="submit" class="btn btn-primary">搜索</button>
 &nbsp;&nbsp;<select name="autoRun"><option value="1" <?php if(request("autoRun") == 1) echo "selected";?>>手动同步</option><option value="2" <?php if(request("autoRun") == 2) echo "selected";?>>自动同步</option></select>
  &nbsp;&nbsp;<button type="button" id="updateUser" class="btn btn-primary">同步到微信端</button>
  &nbsp;&nbsp;<button type="button" id="removeUser" class="btn btn-primary">同步删除微信端</button>
  
  &nbsp;&nbsp;<select name="removeStatus">
    <option value="1">仅删除微信端</option>
    <option value="2">逻辑删除本地数据</option>
  </select>
  
 </form>

 <table width="100%" class="table table-bordered table-hover definewidth m10 table-striped">
   <thead>
     <tr>
       <th width="5%" align="center"><input name="checkAll" type="checkbox" value=""></th>
       <th width="8%" align="center">姓名</th>
       <th width="8%" align="center">部门</th>
       <th width="6%" align="center">性别</th>
       <th width="9%" align="center">手机号</th>
       <th width="10%" align="center">出生年月</th>
       <th width="9%" align="center">文化程度</th>
       <th width="7%" align="center">入党时间</th>
       <th width="9%" align="center">来本单位时间</th>
       <th width="10%" align="center">党费交纳至</th>
       <th width="8%" align="center">状态</th>
       <th width="11%" align="center">处理结果</th>
     </tr>
   </thead>
   <?php
   if($list){
    foreach($list as $k=>$val){
  ?>
   <tr class="listview">
     <td align="center"><input name="userid[]" type="checkbox" value="<?=$val["userid"]?>"></td>
     <td align="center"><?=$val["name"]?></td>
     <td align="center"><?=$val["party_name"]?></td>
     <td align="center"><?=($val["gender"] == 1)?"男":"女"?></td>
     <td align="center"><?=$val["mobile"]?></td>
     <td align="center"><?=$val["bothday"]?></td>
     <td align="center"><?=$val["education"]?></td>
     <td align="center"><?=$val["joinPartyDate"]?></td>
     <td align="center"><?=$val["entrydate"]?></td>
     <td align="center"><?=($val["isPartyMember"] == 1 && $val["year"]>0 && $val["month"]>0)?sprintf("%d年%d月",$val["year"],$val["month"]):""?></td>
     <td align="center"><?=($val["status"]==4)?'<font color="red">未关注</font>':(($val["status"]==2)?'<font color="#FFFF00">已禁用</font>':'已关注')?></td>
     <td align="center"><span class="status"></span></td>
   </tr>
   <?php }?>
   <?php }else {?>
   <tr>
     <td height="30" colspan="13" align="center"><div class="nodata">
       <?=L("base.nodata")?>
     </div></td>
   </tr>
   <?php }?>
 </table>
 <table width="100%" class="definewidth m10">
  <tr>
    <td align="right"><?=L("base.pageTotalCount",$total)?> <?=$pagelink?></td>
  </tr>
</table>
</body>
</html>
<script>
$(function(){
	
	var userids = [];
	var index = 0;
	
	var autoRun = $(":input[name='autoRun']").find("option:selected").val();
	if(autoRun == 2){
		$(":input[name='checkAll']").click();
		$(":input[name='userid[]']").attr("checked",true);
		userids = [];
		index = 0;
		if($(":input[name='userid[]']:checked").length > 0){
			$(":input[name='userid[]']:checked").each(function(){
				userids.push($(this).val());
			})
			
			updateUser(index);
		}else{
			Control.message("请至少选择一条记录！");
		}
	}
	
	Control.checkAll({items:'userid[]'});
	
	$("#updateUser").click(function(){
		userids = [];
		index = 0;
		if($(":input[name='userid[]']:checked").length > 0){
			$(":input[name='userid[]']:checked").each(function(){
				userids.push($(this).val());
			})
			
			updateUser(index);
		}else{
			Control.message("请至少选择一条记录！");
		}
	})
	
	$("#removeUser").click(function(){
		userids = [];
		index = 0;
		if($(":input[name='userid[]']:checked").length > 0){
			Control.confirm(function(){
				$(":input[name='userid[]']:checked").each(function(){
					userids.push($(this).val());
				})
				
				removeUser(index);
			});

		}else{
			Control.message("请至少选择一条记录！");
		}
	})
	
	function updateUser(index){
		Request.get("<?=autolink(array(get('class'),'updateUser'))?>",{userid:userids[index]},function(res){
			var $this = $(":input[name='userid[]']:checked").eq(index);
			var num = $(":input[name='userid[]']").index($this);
			if(res.errcode == 0){
			    $(".status").eq(num).addClass("success").text("同步成功");
			}else{
				 $(".status").eq(num).addClass("warning").text("同步失败："+res["errmsg"]);
			}
			index++;
			if(userids[index]){
				updateUser(index);
			}else{
				var autoRun = $(":input[name='autoRun']").find("option:selected").val();
			    if(autoRun == 1){
					Control.message("全部同步完成！");
				}else{
				    if($(".nextPage").length>0){
						var url = $(".nextPage").attr("href");
						if(autoRun == 2){
							if(url.indexOf("autoRun") == -1){
								url = url + "&autoRun=2";
							}
						}
						location.href = url;
					}
				}
				
			}
		})
	}
	
	function removeUser(index){
		var status = $(":input[name='removeStatus']").find("option:selected").val();
		Request.get("<?=autolink(array(get('class'),'removeUser'))?>",{userid:userids[index],status:status},function(res){
			var $this = $(":input[name='userid[]']:checked").eq(index);
			var num = $(":input[name='userid[]']").index($this);
			if(res.errcode == 0){
			    $(".status").eq(num).addClass("success").text("删除成功");
			}else{
				 $(".status").eq(num).addClass("warning").text("删除失败："+res["errmsg"]);
			}
			index++;
			if(userids[index]){
				removeUser(index);
			}else{
				Control.message("删除成功");
			}
		})
	}

});

</script>
