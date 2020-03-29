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
		
		.table input[type='text'],.table select{ width:270px;}

    </style>
</head>
<body>
<form id="form1" action="<?=autolink(array(get('class'),'saveFee'))?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
	
	<tbody class="partyUser">
	
    <tr>
      <td width="10%" class="tableleft">党费交纳至</td>
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
		<input name="userids" type="hidden" value="<?=request("userids")?>">
       <label></label></td>
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
		if($(":input[name='year']").val() == 0 || $(":input[name='month']").val() == 0){
			Control.message("请选择党费交纳至年月",function(){});
			return false;
		}
	});
});
</script>
