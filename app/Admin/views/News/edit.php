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
<script type="text/javascript" src="__PUBLIC__/js/uploadElement.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/ueditor.all.js"></script>

<script type="text/javascript" src="/ueditor/xiumi-ue-dialog-v5.js"></script>
<link rel="stylesheet" href="/ueditor/xiumi-ue-v5.css">

<!--日期插件-->
<link href="__PUBLIC__/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="__PUBLIC__/plugins/datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" media="all">
<script src="__PUBLIC__/plugins/layui/layui.js"></script>
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
            <td class="tableleft">分类</td>
            <td><?=HtmlControl()->Select("typeid",$newsType,$row["typeid"])->field('name','typeid')->setDefault('- 请选择 -',0)->create();?></td>
        </tr>
	
        <tr>
            <td class="tableleft">标题</td>
            <td><input name="title" type="text" id="title" size="60" data-required="{required:true}" value="<?=$row['title']?>">
                <label class="red">*</label></td>
        </tr>

        <tr>
            <td class="tableleft">封面图片</td>
            <td><button type="button" name="button" id="uploadImg" class="btn btn-primary"> <i class="layui-icon">&#xe67c;</i> 选择图片  </button>
                <div class="PicPannel">
                    <ul>
                    </ul>
                </div>
                <p>
                    <label class="red">推荐尺寸：900像素x500像素,不超过100k </label>
                </p></td>
        </tr>

		
        <tr>
            <td class="tableleft">摘要</td>
            <td>
			<textarea name="description" cols="60" rows="3" class="input-xxlarge" id="description"><?=$row['description']?></textarea></td>
        </tr>
        <tr>
            <td class="tableleft">正文</td>
            <td><textarea name="content" cols="100" rows="5" id="content" style="width:500px;" data-required="{required:true}"><?=$row['content']?></textarea><label class="red">*</label>            </td>
        </tr>
        <tr>
            <td class="tableleft"></td>
            <td><button type="button" name="Submit" data-value="1" class="btn btn-primary"> 保存</button>
                <button type="button" name="Submit" data-value="2" class="btn btn-primary"> 预览</button>
                <input type="hidden" name="preview" value="0">            </td>
        </tr>
    </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){
	Control.onSubmit(function(obj){
		var value = obj.attr("data-value");
		if(value == 3){
			//artDialog.open("<?=autolink(array(get('class'),'publish','newsid'=>get("newsid")))?>",{id:'publish',title:"发布",width:"80%",height:"90%"}).lock();
			return false;
		}else{
			if(value == 2){
				$(":input[name='preview']").val(1);
				return true;
			}else{
				$(":input[name='preview']").val(0);
				return true;
			}
		}
	});
	
	try{
		Control.UEedit('content');
	}catch(err){}
	
	$("#uploadImg").click(function(){
		artDialog.open("<?=autolink(array(get('class'),'uploadImg'))?>",{id:'uploadImg',title:"选择封面图",width:"800px",height:"70%"}).lock();
	})
	
	var picList = {};
	<?php if($row["picture"] != '') {?>
	picList = {filename:'<?=$row["picture"]?>'};
	<?php }?>
	uploadPicElement(picList);
})

function upload(filename){
	uploadPicElement({filename:filename});
}
</script>
