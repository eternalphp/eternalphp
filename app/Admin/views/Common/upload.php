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


<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" media="all">
<script src="__PUBLIC__/plugins/layui/layui.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/cropper/amazeui.cropper.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/cropper/cropper.min.js"></script>

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


        .imgPannel{ width:440px; height:245px; margin:40px auto;background-color: #F2F2F5; text-align:center;}
		.uploadBtn{ height:30px; text-align:center; padding-top:100px;}
		.cropper_imageWaiting, .cropper_noImage {
			text-align: center;
			padding-top: 30%;
			color: #aaa;
		}
		.img_container{ position:relative; display:none;}
		.buttons{ height:40px; line-height:40px; text-align:right;}
		.buttons button{ margin-right:10px;}
    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered m10">
    <tr>
      <td>
	      <div class="imgPannel">
		  	 <div class="uploadBtn"><button type="button" name="button" id="upload" class="btn"> 选择图片 </button></div>
			 <div class="img_container">
			 	<div class="cropper_imageWaiting">加载图片中...</div>
				<div style="width:440px; height:245px;position:absolute;top:0;left:0">
					<img class="js_avatar_image" src="" style="visibility: hidden;max-width:440px; max-height:245px;line-height: 245px;">
				</div>
			 </div>
		  </div>
	  </td>
    </tr>
  </table>
  <div class="buttons">
	  <button type="button" name="button" id="submit" class="btn btn-primary"> 确定 </button>
	  <button type="button" name="button" id="cancel" class="btn"> 取消 </button>
  </div>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){
	layui.use('upload', function(){
		var upload = layui.upload;

		//执行实例
		var uploadInst = upload.render({
			elem: '#upload',
			url: '<?=autolink(array('upload','upload'))?>',
			accept:'image',
			exts:'<?=C("uploadImageExts")?>',
			size:<?=C("uploadImageSize")?>,
			field:'Filedata',
			done: function(res){
				if(res.filename != ''){
					$(".js_avatar_image").attr("src",res.filename);
					$(".js_avatar_image").css("visibility","visible");
					$(".img_container").show();
					$(".uploadBtn").hide();
					
					var width = res.width;
					var height = res.height;
					
					var newHeight = Math.ceil(440*height/width);

					if(newHeight > 245){
						var newWidth = Math.ceil(245*width/height);
						newHeight = 245;
					}else{
						newWidth = 440;
					}

					$(".js_avatar_image").cropper({
						aspectRatio: 2.35/1,
						movable:true,
						resizable:false,
						zoomable:false,
						viewMode:1,
						minContainerWidth:440,
						minContainerHeight:245,
						minCropBoxWidth:newWidth,
						minCropBoxHeight:newHeight
					});
				}
			},
			error: function(){

			}
		});
	  
	});
	
	$("#submit").click(function(){
		var imgData = $('.js_avatar_image').cropper('getData', true);
		imgData.filename = $('.js_avatar_image').attr("src");
		if(imgData){
			$.ajax({
				url:"<?=autolink(array('upload','cropper'))?>",
				type:"POST",
				data:imgData,
				dataType:"json",
				success:function(res){
					if(res.errcode == 0){
						var win = art.dialog.open.origin;//来源页面
						win.upload(res.filename);
						Control.close('uploadImg');
					}else{
						Control.message("上传失败");
					}
				}
			})
		}
	})
	
	$("#cancel").click(function(){
		Control.close('uploadImg');
	})
})
</script>