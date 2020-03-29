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
<script type="text/javascript" src="__PUBLIC__/js/uploader.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<link href="__PUBLIC__/plugins/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="__PUBLIC__/plugins/uploadify/jquery.uploadify-3.1.js"></script>
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
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
    <table width="100%" class="table table-bordered table-hover m10">
        <tr>
            <td class="tableleft">分类标题</td>
            <td><input name="title" type="text" id="title" size="60" data-required="{required:true}" >
                <label class="red">*</label></td>
        </tr>
        <tr>
            <td class="tableleft">上传图片</td>
            <td>
			<input type="text" id="avatarUpload" value="" />
                <div class="PicPannel">
                    <ul>
                    </ul>
                </div>
                <p>
                    <label class="red">推荐尺寸：900像素x500像素,不超过100k </label>
                </p>
            </td>
        </tr>
        <tr>
            <td class="tableleft"></td>
            <td><button type="button" name="Submit" class="btn btn-primary" style="width:120px;"> 确认 </button></td>
        </tr>
    </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function() {
	Control.onSubmit();
	
	$("#avatarUpload").uploader();
})
</script>
