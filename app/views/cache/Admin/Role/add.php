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
	
	
<style type="text/css">
	.schoolDisplay{ display:none;}
	.viewRuleBtn{ height:30px; line-height:30px;}
	.viewRuleBtn a{ text-decoration:none; color:#006600;}
	.viewRule{ display:none;}
	
	.panel {
		border: 1px solid #c3c3d6;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
	
	.panel .panel-header {
		padding: 9px;
		line-height: 18px;
		background-color: #f4f4f4;
		background-image: -moz-linear-gradient(top,#f8f8f8,#eee);
		background-image: -ms-linear-gradient(top,#f8f8f8,#eee);
		background-image: -webkit-gradient(linear,0 0,0 100%,from(#f8f8f8),to(#eee));
		background-image: -webkit-linear-gradient(top,#f8f8f8,#eee);
		background-image: -o-linear-gradient(top,#f8f8f8,#eee);
		background-image: linear-gradient(top,#f8f8f8,#eee);
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f8f8f8',endColorstr='#eeeeee',GradientType=0);
	}
	
	.panel .panel-body {
		padding: 9px;
	}
	
	.panel .panel-header h3 {
		line-height: 18px;
		font-size: 12px;
		margin:0px;
	}
	
	.panel{background-color:#FFFFFF;}
	.rolePannel{ width:100%; border: 1px solid #c3c3d6;border-radius:4px; }
	.rolePannel ul{ padding:0px; margin:0px;}
	.rolePannel ul dt{ height:35px; line-height:35px; background-color:#F6F6F6; border-bottom:#CCCCCC 1px solid; text-indent:10px;}
	.rolePannel ul li{ list-style:none; margin-left:20px;}
	.rolePannel ul li div{line-height:25px; margin:3px 10px;}
	.rolePannel ul li p{ display:block; height:25px; line-height:25px; margin:3px; padding: 0px 0px 0px 30px;}



</style>


</head>

<body>

	<!-- Main content -->
	<div class="content-wrapper" style="display:block;">
	
<div class="content">
  <div class="panel page-header border-top-primary" style="padding-bottom: 0;">
    <div class="page-header-content">
      <div class="page-title">
        <h5> <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">角色管理</span> </h5>
      </div>
    </div>
    <div class="breadcrumb-line breadcrumb-line-wide" style="box-shadow: none;">
      <ul class="breadcrumb">
        <li><a href="/admin/"><i class="icon-home2 position-left"></i> Home</a></li>
        <li><a href="/admin/role/">角色管理</a></li>
        <li class="active">添加角色</li>
      </ul>
    </div>
  </div>
  <form action="/admin/role/save" class="form-validate-jquery" method="post">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">添加角色</h5>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <label>角色名称:</label>
          <input type="text" name="rolename" class="form-control" placeholder="角色名称" value="<?=$row['rolename']?>" />
        </div>
        <div class="form-group">
          <label class="display-block">状态:</label>
          <label class="radio-inline">
          <input type="radio" name="status" value="1" class="styled" <?php if($row["status"] == 1) {?>checked="checked" <?php }?>>
          启用 </label>
          <label class="radio-inline">
          <input type="radio" name="status" value="2" class="styled" <?php if($row["status"] == 2) {?>checked="checked" <?php }?>>
          禁用 </label>
        </div>
        <div class="form-group">
          <label>排序:</label>
          <input type="text" name="sort" class="form-control" placeholder="请输入数字" value="<?=$row['sort']?>" />
        </div>
        <div class="form-group">
          <label>权限设置:</label>
          <div class="viewRuleBtn"> <a href="jsvascript:void(0)" class="switch"> <i class="icon-chevron-down"></i> <span>展开设置权限</span> </a> </div>
          <div class="viewRule">
		  
		  	<?php foreach($menus as $val) {?><div class="panel" style="margin-bottom:10px;">
              <div class="panel-header">
                <h3 style="margin-bottom:0px;">
                  <input name="menuid[]" class="root" type="checkbox" value="<?=$val['menuid']?>">
                  <?=$val['name']?></h3>
              </div>
              <div class="panel-body">
			  	
				
                <div class="rolePannel">
				
					<?php foreach($val["menus"] as $menu) {?><ul class="menuPannel">
                    <dt>
                      <input name="menuid[]" type="checkbox" class="menu" value="<?=$menu['menuid']?>">
                      <?=$menu['name']?></dt>
					  
					  <?php foreach($menu["items"] as $item) {?><li class="itemPannel">
                      <div>
                        <input name="itemid[]" type="checkbox" class="item" value="<?=$item['menuid']?>">
                        <?=$item["name"]?></div>
                      <p>
					    <?php foreach($item["actions"] as $action) {?><input name="actionid[<?=$item['menuid']?>][]" type="checkbox" class="action" value="<?=$action['actionid']?>">
                        <?=$action["name"]?>
						<?php }?>
					   </p>
                    </li>
					<?php }?>
                  </ul>
				  <?php }?>
                </div>
				
				
              </div>
            </div>
			<?php }?>
			
          </div>
        </div>
        <div class="form-group">
          <label>备注:</label>
          <textarea name="remark" rows="5" cols="5" class="form-control" placeholder="备注"><?=$row['remark']?></textarea>
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-primary">提交 <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        <input name="roleid" type="hidden" value="<?=$row['roleid']?>" />
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
			rolename: "required",
			url: {
				required:true,
			}
		},
		messages: {
			name: "请输入角色名称"
		},
	    submitHandler: function(form){
			var formData = $(form).serializeArray(); 
			console.log(formData);
			var data = {};
			$.each(formData, function() {
			   data[this.name] = this.value;
			});
			
			var values = [];
			$(":input[name='actionids']:checked").each(function(){
				values.push($(this).val());
			})
			data["actionids"] = values;
			  
		    $.ajax({
				url:"/admin/role/save",
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
						setTimeout(function(){
							location.href = "/admin/role";
						},500);
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
	
	function checkRole(){
		$(".root").click(function(){
			var index = $(".root").index($(this));
			if($(this).is(':checked')){
				$(".rolePannel").eq(index).find(":input").attr("checked",true);
			}else{
				$(".rolePannel").eq(index).find(":input").attr("checked",false);
			}
		})
		
		$(".menu").click(function(){
			var index = $(".menu").index($(this));
			if($(this).is(':checked')){
				$(".menuPannel").eq(index).find(":input").attr("checked",true);
				$(this).closest(".panel").find(".root").attr("checked",true);
			}else{
				if($(this).siblings(":checked").length ==0) $(".menuPannel").eq(index).find(":input").attr("checked",false);
				if($(this).closest(".panel").find(".menu:checked").length == 0) $(this).closest(".panel").find(".root").attr("checked",false);
			}
		})
		
		$(".item").click(function(){
			var index = $(".item").index($(this));
			if($(this).is(':checked')){
				$(".itemPannel").eq(index).find(":input").attr("checked",true);
				$(this).closest(".panel").find(".root").attr("checked",true);
				$(this).closest(".menuPannel").find(".menu").attr("checked",true);
			}else{
				if($(this).siblings(":checked").length ==0) $(".itemPannel").eq(index).find(":input").attr("checked",false);
				if($(this).closest(".menuPannel").find(".item:checked").length == 0) $(this).closest(".menuPannel").find(".menu").attr("checked",false);
				if($(this).closest(".panel").find(".menu:checked").length == 0) $(this).closest(".panel").find(".root").attr("checked",false);
			}
		})
		
		$(".action").click(function(){
			if($(this).is(':checked')){
				$(this).closest(".panel").find(".root").attr("checked",true);
				$(this).closest(".menuPannel").find(".menu").attr("checked",true);
				$(this).closest(".itemPannel").find(".item").attr("checked",true);
			}else{
				if($(this).siblings(":checked").length ==0) $(this).closest(".itemPannel").find(".item").attr("checked",false);
				if($(this).closest(".menuPannel").find(".item:checked").length == 0) $(this).closest(".menuPannel").find(".menu").attr("checked",false);
				if($(this).closest(".panel").find(".menu:checked").length == 0)  $(this).closest(".panel").find(".root").attr("checked",false);
			}
		})
	}

	checkRole();
	
	var viewRule = false;
	$(".switch").click(function(){
		if(viewRule == false){
			$(this).find("span").text("隐藏设置");
			$(this).find("i").attr("class","icon-chevron-up");
			$(".viewRule").show();
			viewRule = true;
		}else{
			$(this).find("span").text("展开设置权限");
			$(this).find("i").attr("class","icon-chevron-down");
			$(".viewRule").hide();
			viewRule = false;
		}
	})

    // Reset form
    $('#reset').on('click', function() {
        validator.resetForm();
    });
</script>
