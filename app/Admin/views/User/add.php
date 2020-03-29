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
<script type="text/javascript" src="__PUBLIC__/js/HtmlControl.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/ztree/zTreeStyle.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/ztree/jquery.ztree.excheck-3.5.js"></script>

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
		
		.table input[type='text'],.table select{ width:270px;}

    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
    <tr>
      <td width="10%" class="tableleft">姓名</td>
      <td><input name="name" type="text" id="name" class="input-xlarge" data-required="{required:true}"><span class="red">*</span></td>
    </tr>
    <tr>
      <td class="tableleft">性别</td>
      <td>
	  <select name="gender" id="gender" class="input-xlarge">
          <option value="1">男</option>
          <option value="2">女</option>
       </select>	  </td>
    </tr>
	
    <tr>
      <td class="tableleft">身份证号</td>
      <td> <input name="idcard" type="text" class="input-xlarge" data-required="{required:true}" value=""> <span class="red">*</span></td>
    </tr>
	
    <tr>
      <td class="tableleft">部门</td>
      <td> 
	  <div class="userListPannel partyList"><ul> <div class="selectParty"><a href="javascript:void(0)">选择部门</a></div> </ul></div>
	  <input name="partyid" type="hidden" value="">
	  </td>
    </tr>
    <tr>
      <td class="tableleft">党内职位</td>
      <td><?=HtmlControl()->Select("positionid",$position)->field("name","positionid")->setDefault(" - 请选择 - ",0)->create();?> &nbsp;&nbsp;&nbsp;&nbsp;<?=HtmlControl()->Select("other_positionid",$position)->field("name","positionid")->setDefault(" - 请选择 - ",0)->create();?></td>
    </tr>
    <tr>
      <td class="tableleft">手机号</td>
      <td><input name="mobile" type="text" id="mobile" class="input-xlarge" data-required="{required:true,mobile:true}"> <span class="red">*</span></td>
    </tr>
	
    <tr>
      <td class="tableleft">用户身份</td>
      <td><?=HtmlControl()->Select("identityid",$identity)->field("title","identityid")->setDefault(" - 请选择 - ",0)->create();?></td>
    </tr>
    <tr>
      <td class="tableleft">文化程度</td>
      <td><?=HtmlControl()->Select("eduid",$education)->field("name","eduid")->setDefault(" - 请选择 - ",0)->create();?>     </td>
    </tr>
    <tr>
      <td class="tableleft">政治面貌</td>
      <td><?=HtmlControl()->Select("politicalid",$political)->field("name","politicalid")->setDefault(" 请选择 ",5)->create();?></td>
    </tr>
	
    <tr>
      <td class="tableleft">是否党员</td>
      <td>
        <input name="isPartyMember" type="radio" value="1" checked>
      是
      <input type="radio" name="isPartyMember" value="2">
      否
      <label></label></td>
    </tr>
		
    <tr>
      <td class="tableleft">来本单位日期</td>
      <td><input name="entrydate" type="text" id="entrydate" class="input-xlarge date"> </td>
    </tr>
	
	<tbody class="partyUser">
    <tr>
      <td class="tableleft">入党日期</td>
      <td><input name="joinPartyDate" type="text" id="joinPartyDate" class="input-xlarge date"></td>
    </tr>
	
    <tr>
      <td class="tableleft">党费交纳至</td>
      <td>
        <select name="year" style="width:150px;">
          <option value="0">请选择年</option>
		  <?php for($year = date("Y",strtotime("-10 year"));$year<=date("Y");$year++){?>
		  <option value="<?=$year?>" <?php if($year == date("Y")) echo "selected";?>><?=$year?>年</option>
		  <?php }?>
        </select>
		
        <select name="month" style="width:150px;">
          <option value="0">请选择月</option>
		  <?php for($month = 1;$month<=12;$month++){?>
		  <option value="<?=$month?>"><?=$month?>月份</option>
		  <?php }?>
        </select>
      <label></label></td>
    </tr>
	</tbody>
	
	<tbody class="register">
    <tr>
      <td class="tableleft">双报到日期</td>
      <td><input name="register[registerDate][]" type="text" id="registerDate" class="input-xlarge date"></td>
    </tr>
    <tr>
      <td class="tableleft">所在街镇社区</td>
      <td>
        <input name="register[address][]" type="text" id="address">
      <label></label>   <button type="button" name="button" class="btn btn-primary add"> 新增报到 </button> </td>
    </tr>
	</tbody>
	
    <tr>
      <td class="tableleft"></td>
      <td><button type="button" name="Submit" class="btn btn-primary" style="width:120px;"> 保存 </button></td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){


	Control.onSubmit(function(){
		if($(":input[name='isPartyMember']:checked").val() == 1){
			if($(":input[name='year']").val() == 0 || $(":input[name='month']").val() == 0){
				//Control.message("请选择党费交纳至年月",function(){});
				//return false;
			}
		}
		if(getSelectParty().length == 0){
			Control.message("请选择部门！",function(){});
			return false;
		}
	});
	
	$(".selectParty").live('click',function(){
		$(".selectParty").removeClass("selected");
		$(this).addClass("selected");
		artDialog.open("<?=autolink(array(get('class'),'selectParty'))?>",{id:'selectParty',title:"选择部门",width:"40%",height:"60%"}).lock();
	})
	
	function searchParty(keyword){
		treeObj.cancelSelectedNode();
		if(keyword == '') return false;
		var nodes = treeObj.getNodesByFilter(function(node){
			return (node.name.indexOf(keyword) != -1);
		});
		if(nodes){
			$(nodes).each(function(index,node){
				treeObj.selectNode(node,true);
			})
		}
	}
	
	
	$(":input[name='isPartyMember']").change(function(){
		if($(this).val() == 1){
			$(".partyUser,.register").show();
		}else{
			$(".partyUser,.register").hide();
		}
	})
	
	$(".add").click(function(){
			var html = '    <tr>';
		  html += '<td class="tableleft">双报到日期</td>';
		  html += '<td><input name="register[registerDate][]" type="text" id="registerDate" class="input-xlarge date"></td>';
		html += '</tr>';
		html += '<tr>';
		  html += '<td class="tableleft">所在街镇社区</td>';
		  html += '<td>';
			html += '<input name="register[address][]" type="text" id="address">';
		  html += '<label></label>   <button type="button" name="button" class="btn remove"> 删除报到 </button> </td>';
		html += '</tr>';
		$(".register").append(html);
		
		$(".date").datetimepicker({
			language:  language,
			todayHighlight:true,
			autoclose: 1,
			minView: "month",
			format: 'yyyy-mm-dd'
		});
	})
	
	$(".register .remove").live('click',function(){
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().remove();
	})
	
	$(".partyList span.remove").live('click',function(){
		$(this).parent().remove();
	})
	
});

function selectParty(nodes){
	if(nodes){
		var html = '';
		if(nodes.length>0){
			for(var i=0;i<nodes.length;i++){
				html += '<li data-partyid="'+nodes[i].partyid+'"> <span class="user"></span>  <span class="text">'+nodes[i].name+'</span> <span class="remove"></span> </li>';
			}
		}
		
		var dom =  $(".partyList ul");
		dom.html(html);
		dom.append('<div class="selectParty"><a href="javascript:void(0)">修改</a></div>');
		
		var partyids = getSelectParty();
		$(":input[name='partyid']").val(partyids.join(","));
	}
}


function getSelectParty(){
	var partyids = [];
	var dom = $(".partyList ul");
	dom.find("li").each(function(){
		partyids.push($(this).attr("data-partyid"));
	})
	return partyids;
}
</script>
