<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<!-- Global stylesheets -->
	<!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->
	<link href="/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/core.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/components.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/colors.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/common.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/ui/drilldown.js"></script>
	<!-- /core JS files -->

	<script type="text/javascript" src="/assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	
	<script type="text/javascript" src="/assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/datepicker.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/notifications/pnotify.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>

	<!-- Theme JS files -->
	<script type="text/javascript" src="/assets/js/core/app.js"></script>
	<script type="text/javascript" src="/assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="/assets/js/common.js"></script>
	<!-- /theme JS files -->
	
	

</head>

<body>

	<!-- Main content -->
	<div class="content-wrapper" style="display:block;">
	
	<div class="panel page-header border-top-primary" style="padding-bottom: 0;">
		<div class="page-header-content">
			<div class="page-title">
				<h5>
					<i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">菜单管理</span>
				</h5>
			</div>
	
			<div class="heading-elements">
				<button type="button" class="btn btn-primary heading-btn" id="add">添加</button>
				<button type="button" class="btn btn-primary heading-btn" id="edit">编辑</button>
				<button type="button" class="btn btn-primary heading-btn" id="remove">删除</button>
			</div>
		</div>
	
		<div class="breadcrumb-line breadcrumb-line-wide" style="box-shadow: none;">
			<ul class="breadcrumb">
				<li><a href="/admin/"><i class="icon-home2 position-left"></i> Home</a></li>
				<li><a href="/admin/menu/">菜单管理</a></li>
				<li class="active">菜单列表</li>
			</ul>
		</div>
	</div>
	
	<div class="panel panel-flat">
	  <div class="table-responsive">
		<table width="100%" class="table table-striped">
		  <thead>
			<tr>
			  <th width="7%">#</th>
			  <th width="30%">菜单名称</th>
			  <th width="25%">Url</th>
			  <th width="19%" align="center">Icon</th>
			  <th width="19%">管理</th>
			</tr>
		  </thead>
		  <tbody>
		  	<?php foreach($list as $k=>$val) {?><tr>
			  <td align="center"><?=$k+1?></td>
			  <td><?php if($val["parentid"] > 0) {?><?=$val['space']?> <?php }?> <?=$val["name"]?></td>
			  <td><?=$val["url"]?></td>
			  <td align="left"> <span class="<?=$val['style']?>"></span> </td>
			  <td><a href="<?=url("/admin/menu/edit",array('menuid'=>$val["menuid"]))?>"><span class="label label-primary"> 编辑 </label></a>  <a href="<?=url("/admin/menu/remove",array('menuid'=>$val["menuid"]))?>"><span class="label label-danger"> 删除 </label></a></td>
			</tr>
			<?php }?>
		  </tbody>
		</table>
	  </div>
	</div>

	</div>
	<!-- /main content -->

</body>
</html>

<script>
  $(function(){
  	  $("#add").click(function(){
	  	  location.href = "/admin/menu/add";
	  })
  })
</script>
