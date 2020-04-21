<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
				<li><a href="/admin/role/">角色管理</a></li>
				<li class="active">角色列表</li>
			</ul>
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">查询条件</div>
		<div class="panel-body">
			<form id="formSearch" class="form-horizontal">
				<div class="form-group" style="margin-top:15px">
					<label class="control-label col-sm-1" for="txt_search_departmentname">部门名称</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="txt_search_departmentname">
					</div>
					<label class="control-label col-sm-1" for="txt_search_statu">状态</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="txt_search_statu">
					</div>
					<div class="col-sm-4" style="text-align:left;">
						<button type="button" style="margin-left:50px" id="btn_query" class="btn btn-primary">查询</button>
					</div>
				</div>
			</form>
		</div>
	</div>  
	
	<div id="toolbar" class="btn-group">
		<button id="btn_add" type="button" class="btn btn-default">
			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>新增
		</button>
		<button id="btn_edit" type="button" class="btn btn-default">
			<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>修改
		</button>
		<button id="btn_delete" type="button" class="btn btn-default">
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除
		</button>
	</div>
	
	<div class="panel panel-flat">
	  <div class="table-responsive">
		<table width="100%" id="table" class="table table-striped">
		  <thead>
			<tr>
			  <th width="7%" data-field="roleid">#</th>
			  <th width="30%" data-field="rolename">角色名称</th>
			  <th width="25%" data-field="createtime">创建时间</th>
			  <th width="19%" align="center" data-field="statusText">状态</th>
			  <th width="19%">管理</th>
			</tr>
		  </thead>
		</table>
	  </div>
	</div>

	</div>
	<!-- /main content -->

</body>
</html>

<script src="/assets/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="/assets/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="/assets/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script>
  $(function(){
  	  $("#add").click(function(){
	  	  location.href = "/admin/role/add";
	  }) 
	  
	 $('#table').bootstrapTable({
		url: '/admin/role/getList',         //请求后台的URL（*）
		method: 'post',                      //请求方式（*）
		toolbar: '#toolbar',                //工具按钮用哪个容器
		striped: true,                      //是否显示行间隔色
		cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
		pagination: true,                   //是否显示分页（*）
		sortOrder: "asc",                   //排序方式
		queryParams: function(){},//传递参数（*）
		sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
		pageNumber: 1,                       //初始化加载第一页，默认第一页
		pageSize: 10,                       //每页的记录行数（*）
		pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
		columns:[{
			checkbox: true
		},{
			field:"rolename",
			title:"角色名称"
		},{
			field:"createtime",
			title:"创建时间"
		},{
			field:"statusText",
			title:"状态"
		},{
			field:"links",
			title:"管理"
		}]
	 });
  })
</script>
