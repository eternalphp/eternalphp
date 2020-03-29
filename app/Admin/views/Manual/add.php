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

<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/ueditor.all.js"></script>


<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
		#avatarUpload-queue .uploadify-queue-item .cancel a {
			display: none;
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
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
    <table width="100%" class="table table-bordered m10">
        <tr>
            <td class="tableleft">标题</td>
            <td><input name="title" type="text" id="title" size="60" data-required="{required:true}" >
                <label class="red">*</label></td>
        </tr>
        <tr>
            <td class="tableleft">正文</td>
            <td>
			<textarea name="content" cols="100" rows="5" id="content" style="width:500px;" data-required="{required:true}"></textarea><label class="red">*</label>            </td>
        </tr>
        <tr>
            <td class="tableleft"></td>
            <td>
			<button type="button" name="Submit" data-value="1" class="btn btn-primary"> 保存 </button>
            <button type="button" name="Submit"  data-value="2" class="btn btn-primary"> 预览 </button></td>
        </tr>
    </table>
    <input name="preview" type="hidden" value="0">
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>

$(function(){
	Control.onSubmit(function(obj){
		var value = obj.attr("data-value");
		if(value == 2){
			$(":input[name='preview']").val(1);
			return true;
		}else{
			$(":input[name='preview']").val(0);
			return true;
		}
	});
	
	try{
		Control.UEedit('content');
	}catch(err){}

});
</script>
