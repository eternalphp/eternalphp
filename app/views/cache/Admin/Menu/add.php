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
	
<div class="content">

  <div class="panel page-header border-top-primary" style="padding-bottom: 0;">
    <div class="page-header-content">
      <div class="page-title">
        <h5> <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">菜单管理</span> </h5>
      </div>
    </div>
    <div class="breadcrumb-line breadcrumb-line-wide" style="box-shadow: none;">
      <ul class="breadcrumb">
        <li><a href="/admin/"><i class="icon-home2 position-left"></i> Home</a></li>
        <li><a href="/admin/menu/">菜单管理</a></li>
        <li class="active">添加菜单</li>
      </ul>
    </div>
  </div>
  
  <form action="/admin/menu/save" class="form-validate-jquery" method="post">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">添加菜单</h5>
	  </div>
      <div class="panel-body">
	  
        <div class="form-group">
          <label>上级菜单:</label>
			<select data-placeholder="请选择上级菜单" name="parentid" class="select">
				<option value="0">顶级菜单</option>
				<?php foreach($menu as $k=>$val) {?>
				<option value="<?=$val['menuid']?>" <?php if($val["menuid"] == $row["parentid"]) {?> selected="selected" <?php }?>> <?php if($val["parentid"] > 0) {?> &nbsp;&nbsp;&nbsp;&nbsp;<?php }?> <?=$val["name"]?> </option>
				<?php }?>
			</select>
        </div>
	  
        <div class="form-group">
          <label>菜单名称:</label>
          <input type="text" name="name" class="form-control" placeholder="菜单名称" value="<?=$row['name']?>" />
        </div>
		
        <div class="form-group">
          <label>地址:</label>
          <input type="text" name="url" class="form-control" placeholder="Url" value="<?=$row['url']?>"/>
        </div>
		
        <div class="form-group">
          <label class="display-block">显示/隐藏:</label>
          <label class="radio-inline">
		  <input type="radio" name="hidden" value="0" class="styled" <?php if($row["hidden"] == 0) {?> checked="checked" <?php }?>>
          显示
          </label>
          <label class="radio-inline">
		  <input type="radio" name="hidden" value="1" class="styled" <?php if($row["hidden"] == 1) {?> checked="checked" <?php }?>>
          隐藏
          </label>
        </div>
		
        <div class="form-group">
          <label>排序:</label>
          <input type="text" name="sort" class="form-control" placeholder="请输入数字" value="<?=$row['sort']?>" />
        </div>
		
        <div class="form-group">
          <label>图标:</label>
          <input type="text" name="style" class="form-control" placeholder="请输入图标样式" value="<?=$row['style']?>">
        </div>

        <div class="form-group">
          <label>备注:</label>
          <textarea name="remark" rows="5" cols="5" class="form-control" placeholder="备注"><?=$row['remark']?></textarea>
        </div>
		
        <div class="text-right">
          <button type="submit" class="btn btn-primary">提交 <i class="icon-arrow-right14 position-right"></i></button>
        </div>
		
      </div>
    </div>
  </form>
</div>

	</div>
	<!-- /main content -->

</body>
</html>

<script>
    // Setup validation
    // ------------------------------

    // Initialize
    var validator = $(".form-validate-jquery").validate({
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function(error, element) { //更改错误信息显示的位置

            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo( element.parent().parent().parent().parent() );
                }
                 else {
                    error.appendTo( element.parent().parent().parent().parent().parent() );
                }
            }

            // Unstyled checkboxes, radios
            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo( element.parent().parent().parent() );
            }

            // Inline checkboxes, radios
            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo( element.parent().parent() );
            }

            // Input group, styled file input
            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function(label) { //每个字段验证通过执行函数
            label.addClass("validation-valid-label").text("Success.")
        },
		rules: {
			name: "required",
			url: {
				required:true,
			}
		},
		messages: {
			name: "请输入菜单名称",
			url: "请输入Url"
		},
	    submitHandler: function(form){
			var formData = $(form).serializeArray(); 
			var data = {};
			$.each(formData, function() {
			   data[this.name] = this.value;
			});
			  
		    $.ajax({
				url:"/admin/menu/save",
				type:"POST",
				data:data,
				dataType:"json",
				success:function(res){
					if(res.errcode == 0){
						new PNotify({
							title: '提示',
							text: res.errmsg,
							icon: 'icon-checkmark3',
							type: 'success'
						});
						location.href = "/admin/menu";
					}else{
						new PNotify({
							title: '提示',
							text: res.errmsg,
							icon: 'icon-blocked',
							type: 'error'
						});
					}
				}
			})
	    }
    });


    // Reset form
    $('#reset').on('click', function() {
        validator.resetForm();
    });
</script>
